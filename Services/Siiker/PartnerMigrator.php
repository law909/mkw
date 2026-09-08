<?php

namespace Services\Siiker;

use Entities\Arsav;
use Entities\Fizmod;
use Entities\Kontakt;
use Entities\Orszag;
use Entities\Partner;
use Entities\Valutanem;

/**
 * Aktív partnerek + kontaktok. A webes fiók (webusername/jelszó) nem megy át: a
 * felhasználónév az oldloginname mezőbe kerül, jelszót a vevő újat kér.
 *
 * Aminek nincs helye az MKW-ban, a partner megjegyzésébe kerül szövegként: az alapkedvezmény
 * (az MKW-ban nincs partner-szintű globális kedvezmény), a második telephelytől kezdve a
 * telephelyek, és a partner bankszámlája.
 */
class PartnerMigrator extends AbstractMigrator
{
    const HUADOSZAM = '/^\d{8}-\d-\d{2}$/';
    const MEGJEGYZESPREFIX = '[SIIKer] ';

    private array $telephelyek = [];
    private array $kontaktok = [];
    private array $bankszamlak = [];
    private array $missingOrszag = [];

    public function run(): void
    {
        $partnerMap = $this->loadIdMap(Partner::class, 'migrid');
        $kontaktMap = $this->loadIdMap(Kontakt::class, 'migrid');
        $fizmodMap = $this->loadIdMap(Fizmod::class, 'migrid');
        $valutanemMap = $this->loadIdMap(Valutanem::class, 'migrid');
        $arsavMap = $this->loadIdMap(Arsav::class, 'nev');
        $orszagMap = [];
        foreach ($this->loadIdMap(Orszag::class, 'iso3166') as $iso => $id) {
            $orszagMap[strtoupper($iso)] = $id;
        }

        $this->loadRelated();

        $plain = ['kod', 'fizmod', 'fizhatido', 'alapar', 'alapkedv', 'szlavalutanem', 'vatstatus', 'kulfoldi'];
        $text = ['nev', 'cegnev', 'adoszam', 'euadoszam', 'cjszam', 'telefon', 'mobil', 'fax', 'email', 'honlap',
            'irszam', 'varos', 'utca', 'lirsz', 'lvaros', 'lutca', 'webusername', 'megjegyzes', 'szlamegjegyzes'];
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns($plain, $text)
            . ' FROM ' . $this->src->table('partner') . ' WHERE inaktiv = 0 ORDER BY kod' . $this->limitSql());

        foreach ($rows as $r) {
            $kod = (int)$r['kod'];
            $nev = $this->str($r['nev'], 250);
            if ($nev === '') {
                $this->report->skipped('Partner', 'üres név (kod ' . $kod . ')');
                continue;
            }
            /** @var Partner $p */
            [$p, $isNew] = $this->findOrNew(Partner::class, $partnerMap, $kod);
            $p->setMigrid($kod);
            $p->setNev($nev);
            $p->setInaktiv(false);
            $p->setVendeg(false);

            $cegnev = $this->str($r['cegnev'], 255);
            $p->setSzlanev($cegnev !== '' && $cegnev !== $nev ? $cegnev : '');

            $adoszam = $this->str($r['adoszam'], 20);
            $euadoszam = $this->str($r['euadoszam'], 20);
            if ($adoszam !== '' && !preg_match(self::HUADOSZAM, $adoszam)) {
                // a lengyel partnerek adószáma (PL…) az adoszam mezőben van
                if ($euadoszam === '') {
                    $euadoszam = $adoszam;
                }
                $adoszam = '';
            }
            $p->setAdoszam($adoszam);
            $p->setEuadoszam($euadoszam);
            $p->setCjszam($this->str($r['cjszam'], 20));

            $p->setIrszam($this->str($r['irszam'], 10));
            $p->setVaros($this->str($r['varos'], 40));
            $p->setUtca($this->str($r['utca'], 60));
            $p->setLirszam($this->str($r['lirsz'], 10));
            $p->setLvaros($this->str($r['lvaros'], 40));
            $p->setLutca($this->str($r['lutca'], 60));

            $p->setTelefon($this->str($r['telefon'], 60));
            $p->setMobil($this->str($r['mobil'], 60));
            $p->setFax($this->str($r['fax'], 60));
            $p->setEmail($this->str($r['email'], 100));
            $p->setHonlap($this->str($r['honlap'], 200));

            $p->setFizmod($this->ref(Fizmod::class, $fizmodMap[(string)$r['fizmod']] ?? null));
            $p->setFizhatido((int)$r['fizhatido']);
            $vid = $valutanemMap[(string)$r['szlavalutanem']] ?? null;
            if ($vid) {
                $p->setValutanem($this->ref(Valutanem::class, $vid));
            }
            $alapar = (int)$r['alapar'];
            $arsavId = $alapar ? ($arsavMap[TorzsMigrator::arsavNev($alapar)] ?? null) : null;
            if ($arsavId) {
                $p->setArsav($this->ref(Arsav::class, $arsavId));
            } elseif ($alapar) {
                $this->report->note('Partner ' . $nev . ': ismeretlen ársáv ' . $alapar);
            }
            $p->setVatstatus((int)$r['vatstatus']);
            $p->setOldloginname($this->str($r['webusername'], 100));
            $p->setSzamlaegyeb($this->str($r['szlamegjegyzes']));

            $iso = $this->guessIso($adoszam, $euadoszam, (int)$r['kulfoldi']);
            if ($iso !== null) {
                if (isset($orszagMap[$iso])) {
                    $p->setOrszag($this->ref(Orszag::class, $orszagMap[$iso]));
                } else {
                    $this->missingOrszag[$iso] = ($this->missingOrszag[$iso] ?? 0) + 1;
                }
            }

            $this->applyTelephely($p, $kod);
            $p->setMegjegyzes($this->buildMegjegyzes($r, $kod));

            $this->em->persist($p);
            $isNew ? $this->report->created('Partner') : $this->report->updated('Partner');

            foreach ($this->kontaktok[$kod] ?? [] as $k) {
                /** @var Kontakt $kontakt */
                [$kontakt, $kIsNew] = $this->findOrNew(Kontakt::class, $kontaktMap, $k['kod']);
                $kontakt->setMigrid((int)$k['kod']);
                $kontakt->setPartner($p);
                $kontakt->setNev($this->str($k['nev'], 255));
                $kontakt->setTelefon($this->str($k['telefon'], 40));
                $kontakt->setMobil($this->str($k['mobil'], 40));
                $kontakt->setFax($this->str($k['fax'], 40));
                $kontakt->setEmail($this->str($k['email'], 100));
                $kontakt->setHonlap($this->str($k['honlap'], 200));
                $this->em->persist($kontakt);
                $kIsNew ? $this->report->created('Kontakt') : $this->report->updated('Kontakt');
            }
            $this->tick();
        }
        $this->flushClear();

        foreach ($this->missingOrszag as $iso => $db) {
            $this->report->note('Nincs ' . $iso . ' ország a céltörzsben, ' . $db . ' partner ország nélkül maradt');
        }
    }

    private function loadRelated(): void
    {
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'partner'], ['nev', 'irszam', 'varos', 'utca'])
            . ' FROM ' . $this->src->table('partnertelephely') . ' ORDER BY partner, kod');
        foreach ($rows as $r) {
            if ($this->str($r['nev']) === '' && $this->str($r['varos']) === '' && $this->str($r['utca']) === '') {
                continue;
            }
            $this->telephelyek[(int)$r['partner']][] = $r;
        }
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'partner'], ['nev', 'telefon', 'mobil', 'fax', 'email', 'honlap'])
            . ' FROM ' . $this->src->table('kontakt') . ' ORDER BY partner, kod');
        foreach ($rows as $r) {
            $this->kontaktok[(int)$r['partner']][] = $r;
        }
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'partner'], ['szlaszam', 'swift', 'iban'])
            . ' FROM ' . $this->src->table('partnerbankszamla') . ' ORDER BY partner, kod');
        foreach ($rows as $r) {
            $this->bankszamlak[(int)$r['partner']][] = $r;
        }
    }

    private function applyTelephely(Partner $p, int $kod): void
    {
        $first = $this->telephelyek[$kod][0] ?? null;
        if (!$first) {
            return;
        }
        $p->setSzallnev($this->str($first['nev'], 255));
        $p->setSzallirszam($this->str($first['irszam'], 10));
        $p->setSzallvaros($this->str($first['varos'], 40));
        $p->setSzallutca($this->str($first['utca'], 60));
    }

    /** A meglévő megjegyzés marad, a SIIKer-sorok egyszer kerülnek bele (újrafuttatásra is). */
    private function buildMegjegyzes(array $r, int $kod): string
    {
        $lines = [];
        $megj = $this->str($r['megjegyzes']);
        if ($megj !== '') {
            $lines[] = $megj;
        }
        $alapkedv = (int)$r['alapkedv'];
        if ($alapkedv) {
            $lines[] = self::MEGJEGYZESPREFIX . 'alapkedvezmény: ' . $alapkedv . '%';
        }
        foreach (array_slice($this->telephelyek[$kod] ?? [], 1) as $t) {
            $lines[] = self::MEGJEGYZESPREFIX . 'telephely: ' . trim($this->str($t['nev']) . ', ' . $this->str($t['irszam']) . ' ' . $this->str($t['varos']) . ' ' . $this->str($t['utca']), ', ');
        }
        foreach ($this->bankszamlak[$kod] ?? [] as $b) {
            $lines[] = self::MEGJEGYZESPREFIX . 'bankszámla: ' . trim($this->str($b['szlaszam']) . ' ' . $this->str($b['swift']) . ' ' . $this->str($b['iban']));
        }
        return implode("\n", $lines);
    }

    private function guessIso(string $adoszam, string $euadoszam, int $kulfoldi): ?string
    {
        if (preg_match('/^([A-Z]{2})\d/i', $euadoszam, $m)) {
            return strtoupper($m[1]);
        }
        if ($adoszam !== '' || !$kulfoldi) {
            return 'HU';
        }
        return null;
    }
}
