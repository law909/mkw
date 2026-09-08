<?php

namespace Services\Siiker;

use Entities\Afa;
use Entities\Arfolyam;
use Entities\Arsav;
use Entities\Bankszamla;
use Entities\Fizmod;
use Entities\ME;
use Entities\Raktar;
use Entities\TermekFa;
use Entities\Valutanem;
use Entities\Vtsz;
use mkw\consts;
use mkw\store;

/**
 * A kis törzsek: áfa, valutanem, bankszámla, árfolyam, me, vtsz, fizmod, raktár, ársáv,
 * termékcsoport-fa. A kulcs mindenütt a SIIKer `kod` (migrid / idegenkod), a névvel azonosított
 * törzsekben (me, ársáv) a név.
 */
class TorzsMigrator extends AbstractMigrator
{
    const IDEGENKODPREFIX = 'siiker:';
    const ARSAVPREFIX = 'Sáv ';
    const BESZARSAV = 'Beszerzési ár';
    const ROOTNEV = 'Termék csoportok';

    private array $afaMap = [];
    private array $valutanemMap = [];
    private array $bankszamlaMap = [];
    private array $raktarMap = [];

    public function run(): void
    {
        $this->migrateAfa();
        $this->migrateValutanem();
        $this->migrateBankszamla();
        $this->migrateArfolyam();
        $this->migrateMe();
        $this->migrateVtsz();
        $this->migrateFizmod();
        $this->migrateRaktar();
        $this->migrateArsav();
        $this->migrateTermekFa();
        $this->setDefaultParameters();
    }

    public static function idegenkod($kod): string
    {
        return self::IDEGENKODPREFIX . (int)$kod;
    }

    public static function arsavNev($sav): string
    {
        return self::ARSAVPREFIX . (int)$sav;
    }

    private function migrateAfa(): void
    {
        $map = $this->loadIdMap(Afa::class, 'migrid');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'afaertek', 'rlbkod'], ['afanev', 'navcase'])
            . ' FROM ' . $this->src->table('afatorzs') . ' ORDER BY kod');
        foreach ($rows as $r) {
            /** @var Afa $afa */
            [$afa, $isNew] = $this->findOrNew(Afa::class, $map, $r['kod']);
            $afa->setNev($this->str($r['afanev'], 255));
            $afa->setErtek((float)$r['afaertek']);
            $afa->setRLBKod($r['rlbkod'] !== null ? (int)$r['rlbkod'] : null);
            $afa->setNavcase($this->strOrNull($r['navcase'], 20));
            $afa->setMagyar(true);
            $afa->setMigrid((int)$r['kod']);
            $this->save($afa, $isNew, 'Afa');
        }
        $this->flush();
        $this->afaMap = $this->loadIdMap(Afa::class, 'migrid');
    }

    private function migrateValutanem(): void
    {
        $map = $this->loadIdMap(Valutanem::class, 'migrid');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'kerekit', 'hivatalos', 'mincimlet'], ['nev'])
            . ' FROM ' . $this->src->table('valutanem') . ' ORDER BY kod');
        foreach ($rows as $r) {
            /** @var Valutanem $v */
            [$v, $isNew] = $this->findOrNew(Valutanem::class, $map, $r['kod']);
            $v->setNev($this->str($r['nev'], 6));
            $v->setKerekit($this->bool($r['kerekit']));
            $v->setHivatalos($this->bool($r['hivatalos']));
            $v->setMincimlet((int)$r['mincimlet']);
            $v->setMigrid((int)$r['kod']);
            $this->save($v, $isNew, 'Valutanem');
        }
        $this->flush();
        $this->valutanemMap = $this->loadIdMap(Valutanem::class, 'migrid');
    }

    private function migrateBankszamla(): void
    {
        $map = $this->loadIdMap(Bankszamla::class, 'migrid');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'defavaluta'], ['banknev', 'bankcim', 'szlaszam', 'swift', 'iban'])
            . ' FROM ' . $this->src->table('bankszamla') . ' ORDER BY kod');
        foreach ($rows as $r) {
            /** @var Bankszamla $b */
            [$b, $isNew] = $this->findOrNew(Bankszamla::class, $map, $r['kod']);
            $b->setBanknev($this->str($r['banknev'], 50));
            $b->setBankcim($this->str($r['bankcim'], 70));
            $b->setSzamlaszam($this->str($r['szlaszam'], 255));
            $b->setSwift($this->str($r['swift'], 20));
            $b->setIban($this->str($r['iban'], 20));
            $b->setValutanem($this->ref(Valutanem::class, $this->valutanemMap[(string)$r['defavaluta']] ?? null));
            $b->setMigrid((int)$r['kod']);
            $this->save($b, $isNew, 'Bankszamla');
        }
        $this->flush();
        $this->bankszamlaMap = $this->loadIdMap(Bankszamla::class, 'migrid');

        // a valutanem alapértelmezett bankszámlája csak a bankszámlák után köthető
        $rows = $this->src->fetchAll('SELECT kod, defabankszla FROM ' . $this->src->table('valutanem') . ' WHERE defabankszla IS NOT NULL');
        foreach ($rows as $r) {
            $vid = $this->valutanemMap[(string)$r['kod']] ?? null;
            $bid = $this->bankszamlaMap[(string)$r['defabankszla']] ?? null;
            if ($vid && $bid) {
                $this->em->find(Valutanem::class, $vid)->setBankszamla($this->ref(Bankszamla::class, $bid));
            }
        }
        $this->flushClear();
    }

    private function migrateArfolyam(): void
    {
        $rows = $this->em->createQueryBuilder()
            ->select('a.id AS id, a.datum AS datum, IDENTITY(a.valutanem) AS vid')
            ->from(Arfolyam::class, 'a')
            ->getQuery()->getScalarResult();
        $existing = [];
        foreach ($rows as $r) {
            $datum = $r['datum'] instanceof \DateTimeInterface ? $r['datum']->format('Y-m-d') : substr((string)$r['datum'], 0, 10);
            $existing[$datum . '|' . $r['vid']] = (int)$r['id'];
        }
        $rows = $this->src->fetchAll('SELECT kod, valutanem, datum, ertek FROM ' . $this->src->table('arfolyam')
            . ' WHERE datum IS NOT NULL ORDER BY datum, kod');
        foreach ($rows as $r) {
            $vid = $this->valutanemMap[(string)$r['valutanem']] ?? null;
            if (!$vid) {
                $this->report->skipped('Arfolyam', 'ismeretlen valutanem ' . $r['valutanem'] . ' (kod ' . $r['kod'] . ')');
                continue;
            }
            $key = $r['datum'] . '|' . $vid;
            $id = $existing[$key] ?? null;
            if ($id === 0) {
                // a forrásban ugyanarra a napra több sor is lehet, az első marad
                $this->report->skipped('Arfolyam');
                continue;
            }
            $a = $id ? $this->em->find(Arfolyam::class, $id) : new Arfolyam();
            $a->setDatum(new \DateTime($r['datum']));
            $a->setValutanem($this->ref(Valutanem::class, $vid));
            $a->setArfolyam((float)$r['ertek']);
            if (!$id) {
                $existing[$key] = 0;
            }
            $this->save($a, !$id, 'Arfolyam');
        }
        $this->flushClear();
    }

    private function migrateMe(): void
    {
        $map = $this->loadIdMap(ME::class, 'nev');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns([], ['nev', 'navtipus'])
            . ' FROM ' . $this->src->table('me') . ' ORDER BY kod');
        $done = [];
        foreach ($rows as $r) {
            $nev = $this->str($r['nev'], 255);
            if ($nev === '' || isset($done[$nev])) {
                continue;
            }
            $done[$nev] = true;
            /** @var ME $me */
            [$me, $isNew] = $this->findOrNew(ME::class, $map, $nev);
            $me->setNev($nev);
            $me->setNavtipus($this->strOrNull($r['navtipus'], 30));
            $this->save($me, $isNew, 'ME');
        }
        $this->flushClear();
    }

    private function migrateVtsz(): void
    {
        $map = $this->loadIdMap(Vtsz::class, 'migrid');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'afa'], ['szam', 'szoveg'])
            . ' FROM ' . $this->src->table('vtsz') . ' ORDER BY kod');
        foreach ($rows as $r) {
            /** @var Vtsz $v */
            [$v, $isNew] = $this->findOrNew(Vtsz::class, $map, $r['kod']);
            $v->setSzam($this->str($r['szam'], 255));
            $v->setNev($this->str($r['szoveg'], 255));
            $v->setAfa($this->ref(Afa::class, $this->afaMap[(string)$r['afa']] ?? null));
            $v->setMigrid((int)$r['kod']);
            $this->save($v, $isNew, 'Vtsz');
        }
        $this->flushClear();
    }

    private function migrateFizmod(): void
    {
        $map = $this->loadIdMap(Fizmod::class, 'migrid');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'haladek', 'webes', 'penzmozgassalnemjar'], ['nev', 'tipus', 'navtipus'])
            . ' FROM ' . $this->src->table('fizmod') . ' ORDER BY kod');
        foreach ($rows as $r) {
            /** @var Fizmod $f */
            [$f, $isNew] = $this->findOrNew(Fizmod::class, $map, $r['kod']);
            $f->setNev($this->str($r['nev'], 255));
            if ($isNew) {
                $f->setNevL1('');
            }
            $f->setTipus($this->str($r['tipus'], 1) ?: 'B');
            $f->setHaladek((int)$r['haladek']);
            $f->setWebes($this->bool($r['webes']));
            $f->setNavtipus($this->strOrNull($r['navtipus'], 20));
            $f->setNincspenzmozgas($this->bool($r['penzmozgassalnemjar']));
            $f->setMigrid((int)$r['kod']);
            $this->save($f, $isNew, 'Fizmod');
        }
        $this->flushClear();
    }

    private function migrateRaktar(): void
    {
        $map = $this->loadIdMap(Raktar::class, 'idegenkod');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'mozgat'], ['azon'])
            . ' FROM ' . $this->src->table('raktar') . ' ORDER BY kod');
        foreach ($rows as $r) {
            /** @var Raktar $rk */
            [$rk, $isNew] = $this->findOrNew(Raktar::class, $map, self::idegenkod($r['kod']));
            $rk->setNev($this->str($r['azon'], 50));
            $rk->setMozgat($this->bool($r['mozgat']));
            $rk->setIdegenkod(self::idegenkod($r['kod']));
            $this->save($rk, $isNew, 'Raktar');
        }
        $this->flush();
        $this->raktarMap = $this->loadIdMap(Raktar::class, 'idegenkod');
        $this->em->clear();
    }

    /** A SIIKer-ben a sávnak csak száma van; a név az adminban átnevezhető, az árak id-re mutatnak. */
    private function migrateArsav(): void
    {
        $map = $this->loadIdMap(Arsav::class, 'nev');
        $savok = $this->src->fetchAll('SELECT DISTINCT sav FROM ' . $this->src->table('termekar') . ' WHERE klassz IS NOT NULL ORDER BY sav');
        $nevek = [self::BESZARSAV];
        foreach ($savok as $r) {
            $nevek[] = self::arsavNev($r['sav']);
        }
        foreach ($nevek as $nev) {
            if (isset($map[$nev])) {
                $this->report->updated('Arsav');
                continue;
            }
            $a = new Arsav();
            $a->setNev($nev);
            $this->save($a, true, 'Arsav');
        }
        $this->flushClear();
    }

    private function migrateTermekFa(): void
    {
        $map = $this->loadIdMap(TermekFa::class, 'idegenkod');
        $rows = $this->src->fetchAll('SELECT ' . $this->src->columns(['kod', 'szulokod', 'lathato', 'torolt', 'sorrend'], ['nev', 'leiras', 'htmlleiras', 'rovidleiras'])
            . ' FROM ' . $this->src->table('termekcsoport') . " WHERE nev IS NOT NULL AND nev <> '' ORDER BY kod");
        $enNevek = [];
        if (store::isMultilang()) {
            foreach ($this->src->fetchAll('SELECT termekcsoport, ' . $this->src->text('nev') . ' FROM ' . $this->src->table('termekcsoportnev') . " WHERE nyelv = 'EN'") as $r) {
                $enNevek[(int)$r['termekcsoport']] = $this->str($r['nev'], 255);
            }
        }

        $root = TermekFa::getRoot();
        $nodes = [];
        $parentOf = [];
        foreach ($rows as $r) {
            $kod = (int)$r['kod'];
            $isRoot = !(int)$r['szulokod'];
            if ($isRoot && $root) {
                $nodes[$kod] = $root;
                $this->report->skipped('TermekFa', 'a SIIKer gyökér (' . $r['nev'] . ') = a meglévő gyökér: ' . $root->getNev());
                continue;
            }
            /** @var TermekFa $fa */
            [$fa, $isNew] = $this->findOrNew(TermekFa::class, $map, self::idegenkod($kod));
            $fa->setNev($this->str($r['nev'], 255));
            if (isset($enNevek[$kod])) {
                $fa->setNevL1($enNevek[$kod]);
            }
            $fa->setRovidleiras($this->str($r['rovidleiras']));
            $fa->setLeiras($this->html($r['htmlleiras'], $r['leiras']));
            $fa->setLathato($isRoot || $this->bool($r['lathato']));
            $fa->setInaktiv($this->bool($r['torolt']));
            $fa->setSorrend((int)$r['sorrend']);
            $fa->setIdegenkod(self::idegenkod($kod));
            $this->em->persist($fa);
            $isNew ? $this->report->created('TermekFa') : $this->report->updated('TermekFa');
            $nodes[$kod] = $fa;
            if ($isRoot) {
                $root = $fa;
            } else {
                $parentOf[$kod] = (int)$r['szulokod'];
            }
        }
        if (!$root) {
            $root = new TermekFa();
            $root->setNev(self::ROOTNEV);
            $root->setLathato(true);
            $this->em->persist($root);
            $this->report->created('TermekFa');
        }
        // az id-k a szülő-hozzárendeléshez kellenek (karkod)
        $this->flush();

        foreach ($parentOf as $kod => $szulokod) {
            $parent = $nodes[$szulokod] ?? $root;
            if (!isset($nodes[$szulokod])) {
                $this->report->note('TermekFa ' . $nodes[$kod]->getNev() . ': ismeretlen szülő (' . $szulokod . '), a gyökér alá kerül');
            }
            if ($nodes[$kod]->getParent() !== $parent) {
                $nodes[$kod]->removeParent();
                $nodes[$kod]->setParent($parent);
            }
        }
        $this->flush();
        // a karkod-ot a tár képzi (5 jegyű, láncolt), a termékek tükörmezőivel együtt
        $this->em->getRepository(TermekFa::class)->regenerateKarKod();
        $this->em->clear();
    }

    /** Az alapértelmezések csak akkor íródnak, ha még nincs beállítva semmi. */
    private function setDefaultParameters(): void
    {
        $this->setParameterIfEmpty(consts::Arsav, $this->loadIdMap(Arsav::class, 'nev')[self::arsavNev(2)] ?? null, 'ársáv = Sáv 2');
        $this->setParameterIfEmpty(consts::Valutanem, $this->loadIdMap(Valutanem::class, 'nev')['HUF'] ?? null, 'valutanem = HUF');
        $firstRaktar = $this->src->fetchOne('SELECT MIN(kod) FROM ' . $this->src->table('raktar'));
        $this->setParameterIfEmpty(consts::Raktar, $this->raktarMap[self::idegenkod($firstRaktar)] ?? null, 'raktár = az első SIIKer raktár');
    }

    private function setParameterIfEmpty(string $par, $id, string $label): void
    {
        if (!$id) {
            return;
        }
        $current = store::getParameter($par, '');
        if ($current !== '' && $current !== null) {
            return;
        }
        store::setParameter($par, $id);
        $this->report->note('Paraméter beállítva: ' . $par . ' → ' . $id . ' (' . $label . ')');
    }
}
