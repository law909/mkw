<?php

namespace Services\Siiker;

use Entities\Afa;
use Entities\Arsav;
use Entities\ME;
use Entities\Termek;
use Entities\TermekAr;
use Entities\TermekFa;
use Entities\Valutanem;
use Entities\Vtsz;
use mkw\store;

/**
 * Aktív termékek (`termek` lépés) és minden áruk (`ar` lépés).
 *
 * Árak: a klassz NULL sor a beszerzési ár (PLN/EUR/HUF külön sorban), a klassz 1 sorok a
 * `Sáv N` ársávok; az `[N]*x,yyy` árszabály képletes TermekAr lesz (forrás = Sáv N, százalék =
 * x·100). Az értékek a forrásból íródnak, újraszámolás nem kell.
 */
class TermekMigrator extends AbstractMigrator
{
    const ALAPSAV = 2;
    const ARSZAB = '/^\[(\d+)\]\*(\d+(?:[.,]\d+)?)$/';

    public function run(): void
    {
        $this->migrateTermek();
    }

    public function runAr(): void
    {
        $this->migrateAr();
    }

    private function activeTermekSql(): string
    {
        return 'SELECT kod FROM ' . $this->src->table('termek') . ' WHERE inaktiv = 0';
    }

    private function hufKod(): ?int
    {
        $kod = $this->src->fetchOne('SELECT kod FROM ' . $this->src->table('valutanem') . " WHERE nev = 'HUF' ORDER BY kod LIMIT 1");
        return $kod === false || $kod === null ? null : (int)$kod;
    }

    private function migrateTermek(): void
    {
        $termekMap = $this->loadIdMap(Termek::class, 'migrid');
        $afaMap = $this->loadIdMap(Afa::class, 'migrid');
        $vtszMap = $this->loadIdMap(Vtsz::class, 'migrid');
        $meMap = $this->loadIdMap(ME::class, 'nev');
        $faMap = $this->loadIdMap(TermekFa::class, 'idegenkod');

        $alapar = [];
        $huf = $this->hufKod();
        if ($huf) {
            foreach ($this->src->fetchAll('SELECT termek, netto FROM ' . $this->src->table('termekar') . ' WHERE klassz IS NOT NULL AND sav = ? AND valutanem = ?', [self::ALAPSAV, $huf]) as $r) {
                $alapar[(int)$r['termek']] = (float)$r['netto'];
            }
        }
        $enNevek = [];
        if (store::isMultilang()) {
            foreach ($this->src->fetchAll('SELECT termek, ' . $this->src->text('nev') . ' FROM ' . $this->src->table('termeknev') . " WHERE nyelv = 'EN'") as $r) {
                $enNevek[(int)$r['termek']] = $this->str($r['nev'], 255);
            }
        }

        $plain = ['kod', 'afa', 'vtsz', 'csoportkod', 'lathato', 'ajanlott', 'hozzaszolas', 'nettosuly', 'gyujto', 'bonthato', 'tipus'];
        $text = ['cikkszam', 'nev', 'nev2', 'me', 'leiras', 'htmlleiras', 'rovidleiras', 'kulcsszavak', 'seodescription', 'weboldalcim'];
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns($plain, $text)
            . ' FROM ' . $this->src->table('termek') . ' WHERE inaktiv = 0 ORDER BY kod' . $this->limitSql());

        $cikkszamok = [];
        foreach ($rows as $r) {
            $kod = (int)$r['kod'];
            $nev = $this->str($r['nev'], 255);
            if ($nev === '') {
                $this->report->skipped('Termek', 'üres név (kod ' . $kod . ')');
                continue;
            }
            $cikkszam = $this->str($r['cikkszam'], 50);
            if (isset($cikkszamok[$cikkszam])) {
                $this->report->note('Termek ' . $cikkszam . ' (kod ' . $kod . '): duplikált cikkszám, kod ' . $cikkszamok[$cikkszam] . ' is ez');
            }
            $cikkszamok[$cikkszam] = $kod;
            if ((int)$r['tipus'] === 2) {
                $this->report->note('Termek ' . $cikkszam . ': a SIIKer-ben szolgáltatás, termékként kerül át');
            }

            /** @var Termek $t */
            [$t, $isNew] = $this->findOrNew(Termek::class, $termekMap, $kod);
            $t->setMigrid($kod);
            $t->setCikkszam($cikkszam);
            $t->setNev($nev);
            if (isset($enNevek[$kod])) {
                $t->setNevL1($enNevek[$kod]);
            }

            // a vtsz beállítása az áfát is átírja, ezért a termék saját áfája utána jön
            $vtszId = $vtszMap[(string)$r['vtsz']] ?? null;
            if ($vtszId) {
                $t->setVtsz($this->em->find(Vtsz::class, $vtszId));
            } elseif ((int)$r['vtsz']) {
                $this->report->note('Termek ' . $cikkszam . ': ismeretlen vtsz ' . $r['vtsz']);
            }
            $afaId = $afaMap[(string)$r['afa']] ?? null;
            if ($afaId) {
                $t->setAfa($this->em->find(Afa::class, $afaId));
            }
            $meId = $meMap[$this->str($r['me'])] ?? null;
            if ($meId) {
                $t->setMekod($this->em->find(ME::class, $meId));
            } else {
                $t->setMe($this->str($r['me'], 20));
            }
            $faId = $faMap[TorzsMigrator::idegenkod($r['csoportkod'])] ?? null;
            if ($faId) {
                $t->setTermekfa1($this->em->find(TermekFa::class, $faId));
            } elseif ((int)$r['csoportkod']) {
                $this->report->note('Termek ' . $cikkszam . ': ismeretlen termékcsoport ' . $r['csoportkod']);
            }

            $t->setLeiras($this->html($r['htmlleiras'], $r['leiras']));
            $rovid = $this->str($r['rovidleiras']);
            $t->setRovidleiras($rovid !== '' ? $rovid : $this->str($r['nev2']));
            $t->setSeokeywords($this->str($r['kulcsszavak'], 255));
            $t->setSeodescription($this->str($r['seodescription']));
            $t->setOldalcim($this->str($r['weboldalcim'], 255));
            $t->setLathato($this->bool($r['lathato']));
            $t->setAjanlott($this->bool($r['ajanlott']));
            $t->setHozzaszolas($this->bool($r['hozzaszolas']));
            $t->setSuly((float)$r['nettosuly']);
            $t->setGyujto((float)$r['gyujto']);
            $t->setBonthato($r['bonthato'] === null ? true : $this->bool($r['bonthato']));
            $t->setInaktiv(false);

            if ($t->getAfa()) {
                $t->setNetto($alapar[$kod] ?? 0);
            } else {
                $this->report->note('Termek ' . $cikkszam . ': nincs áfa, ár nélkül marad');
            }
            $this->save($t, $isNew, 'Termek');
        }
        $this->flushClear();
    }

    private function migrateAr(): void
    {
        $termekMap = $this->loadIdMap(Termek::class, 'migrid');
        $valutanemMap = $this->loadIdMap(Valutanem::class, 'migrid');
        $arsavMap = $this->loadIdMap(Arsav::class, 'nev');
        $beszArsav = $arsavMap[TorzsMigrator::BESZARSAV] ?? null;

        $rows = $this->em->createQueryBuilder()
            ->select('ta.id AS id, IDENTITY(ta.termek) AS tid, IDENTITY(ta.arsav) AS aid, IDENTITY(ta.valutanem) AS vid')
            ->from(TermekAr::class, 'ta')
            ->getQuery()->getScalarResult();
        $existing = [];
        foreach ($rows as $r) {
            $existing[$r['tid'] . '|' . $r['aid'] . '|' . $r['vid']] = (int)$r['id'];
        }

        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'termek', 'valutanem', 'klassz', 'sav', 'netto', 'brutto'], ['arszab'])
            . ' FROM ' . $this->src->table('termekar') . ' WHERE termek IN (' . $this->activeTermekSql() . ') ORDER BY termek, klassz, sav');

        $termek = null;
        $termekId = null;
        foreach ($rows as $r) {
            $tid = $termekMap[(string)$r['termek']] ?? null;
            if (!$tid) {
                $this->report->skipped('TermekAr', 'nem migrált termék ' . $r['termek'] . ' (termekar kod ' . $r['kod'] . ')');
                continue;
            }
            $vid = $valutanemMap[(string)$r['valutanem']] ?? null;
            if (!$vid) {
                $this->report->skipped('TermekAr', 'ismeretlen valutanem ' . $r['valutanem'] . ' (termekar kod ' . $r['kod'] . ')');
                continue;
            }
            $arsavNev = $r['klassz'] === null ? TorzsMigrator::BESZARSAV : TorzsMigrator::arsavNev($r['sav']);
            $aid = $arsavMap[$arsavNev] ?? null;
            if (!$aid) {
                $this->report->skipped('TermekAr', 'ismeretlen ársáv ' . $arsavNev);
                continue;
            }

            if ($termekId !== $tid) {
                $termek = $this->em->find(Termek::class, $tid);
                $termekId = $tid;
            }
            if (!$termek->getAfa()) {
                $this->report->skipped('TermekAr', $termek->getCikkszam() . ': nincs áfa');
                continue;
            }

            $key = $tid . '|' . $aid . '|' . $vid;
            $id = $existing[$key] ?? null;
            if ($id === 0) {
                $this->report->skipped('TermekAr', $termek->getCikkszam() . ' ' . $arsavNev . ': ugyanerre a sávra és valutanemre már jött ár');
                continue;
            }
            if ($id) {
                $ta = $this->em->find(TermekAr::class, $id);
            } else {
                $ta = new TermekAr();
                $termek->addTermekAr($ta);
                $ta->setArsav($this->ref(Arsav::class, $aid));
                $ta->setValutanem($this->ref(Valutanem::class, $vid));
                $existing[$key] = 0;
            }

            $forras = $this->parseArszab((string)$r['arszab']);
            if ($forras) {
                $forrasId = $arsavMap[TorzsMigrator::arsavNev($forras['sav'])] ?? null;
                if ($forrasId && $forrasId !== $aid) {
                    $ta->setKepletes(true);
                    $ta->setForrasarsav($this->ref(Arsav::class, $forrasId));
                    $ta->setSzazalek($forras['szazalek']);
                    $ta->setHozzaad(0);
                } else {
                    $this->report->note('TermekAr ' . $termek->getCikkszam() . ' ' . $arsavNev . ': értelmezhetetlen árszabály ' . $r['arszab'] . ', fix ár lett');
                    $ta->setKepletes(false);
                }
            } else {
                $ta->setKepletes(false);
            }
            $ta->setNetto((float)$r['netto']);
            // clear() után a termék is levált, a következő sor újra betölti
            if ($this->save($ta, !$id, 'TermekAr')) {
                $termek = null;
                $termekId = null;
            }
        }
        $this->flushClear();
    }

    /** `[2]*1,455` → forrás sáv 2, 145.5 %. */
    private function parseArszab(string $arszab): ?array
    {
        $arszab = trim($arszab);
        if ($arszab === '' || !preg_match(self::ARSZAB, $arszab, $m)) {
            return null;
        }
        return ['sav' => (int)$m[1], 'szazalek' => round((float)str_replace(',', '.', $m[2]) * 100, 4)];
    }
}
