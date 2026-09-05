<?php

namespace Services;

use Entities\Termek;
use Entities\TermekValtozat;

/**
 * FIFO készletértékelés: minden bevételezés a maga beszerzési árán képez egy réteget, a
 * kiadások a legrégebbi rétegből fogynak, a készlet értéke a még bent lévő rétegeké.
 *
 * A számítás egyetlen rendezett lekérdezésből fut végig, készletcsoportonként
 * (raktár, termék, változat) egy rétegsorral – nyers DBAL-lal, soronként iterálva, mert
 * az ORM a negyedmillió sort egyben materializálná. A nyitott rétegek a `fiforeteg`, a
 * csoportösszesítő a `fifoertek` táblába kerül.
 *
 * Két szabály tartja együtt a rendszer többi részével:
 *
 * 1. A szűrés betűre a `KeszletService::getKeszletWhereSql()`-é. Stornóra nem szűrünk.
 * 2. **Tartozás-átvitel**: ha egy kiadásra nincs elég nyitott réteg, a hiány nem vész el,
 *    hanem a csoport tartozása lesz, és a következő bevét először azt törleszti. Enélkül a
 *    menet közben negatívba forduló csoportok készlete a rétegekben bent ragadna. Ezzel a
 *    szabállyal a rétegek mennyisége csoportonként pontosan `max(0, KeszletService készlet)`.
 */
class FifoService
{

    /** ennyi soronként megy egy multi-row INSERT */
    private const INSERTKOTEG = 500;

    /** a rétegek mennyisége egész "centi-darabban" számolódik, hogy ne legyen lebegőpontos maradék */
    private const SKALA = 100;

    private static $ertekCache = [];

    /**
     * Teljes újraszámolás minden csoportra.
     *
     * @return array riport a futásról
     */
    public function recalculateAll(): array
    {
        return $this->withLock(function () {
            $maxid = $this->getValtozasMaxId();
            $eredmeny = $this->calculate(null, null, [], []);
            $this->store($eredmeny, true, []);
            $this->clearValtozas($maxid);
            return $this->report($eredmeny, count($eredmeny));
        });
    }

    /**
     * Csak a `fifovaltozas`-ban megjelölt csoportok újraszámolása.
     *
     * A sorrend kötött: előbb rögzítjük, meddig dolgozunk fel, és csak a végén törlünk
     * eddig – különben a futás alatt beérkező jelölések elvesznének.
     */
    public function recalculateDirty(): array
    {
        return $this->withLock(function () {
            $maxid = $this->getValtozasMaxId();
            if (!$maxid) {
                return $this->report([], 0);
            }
            $csoportok = $this->getValtozasGroups($maxid);
            $eredmeny = $this->calculate(null, null, [], $csoportok);
            $this->store($eredmeny, false, $csoportok);
            $this->clearValtozas($maxid);
            return $this->report($eredmeny, count($csoportok));
        });
    }

    /**
     * @param array $csoportok [raktarid, termekid, valtozatid] hármasok
     */
    public function recalculateGroups(array $csoportok): array
    {
        if (!$csoportok) {
            return $this->report([], 0);
        }
        return $this->withLock(function () use ($csoportok) {
            $eredmeny = $this->calculate(null, null, [], $csoportok);
            $this->store($eredmeny, false, $csoportok);
            return $this->report($eredmeny, count($csoportok));
        });
    }

    /** Egy termék összes csoportja – a termék karbantartó „számold újra" gombjáé. */
    public function recalculateTermek($termekid): array
    {
        $csoportok = $this->getTermekGroups((int)$termekid);
        if (!$csoportok) {
            return $this->report([], 0);
        }
        return $this->recalculateGroups($csoportok);
    }

    /**
     * Egy múltbeli napra érvényes FIFO érték – **nem tárol**. A Készlet kimutatás hívja,
     * ha nem a mai napot kérték.
     *
     * @param string $datum ISO dátum
     * @param array $termekids a riporton szereplő termékek; üresen minden termék
     *
     * @return array csoportkulcs => sor
     */
    public function calculateAsOf($datum, $raktarid = null, array $termekids = []): array
    {
        return $this->calculate($datum, $raktarid, $termekids, []);
    }

    // ------------------------------------------------------------------
    // a számítás
    // ------------------------------------------------------------------

    /**
     * A FIFO menet. A hívó szűkítheti dátumra, raktárra, termékekre vagy konkrét
     * csoportokra; a szűkítés csak a feldolgozott sorok halmazát vágja, az algoritmus
     * ugyanaz – nincs két külön ág, ami elcsúszhatna.
     *
     * Dátumra visszafelé szűkíteni tilos: a csoportot mindig az első mozgásától kell látni.
     *
     * @return array csoportkulcs => ['raktarid','termekid','valtozatid','mennyiseg','ertek',
     *                                'egysegertek','becsult','retegdb','retegek']
     */
    private function calculate($datum, $raktarid, array $termekids, array $csoportok): array
    {
        $conn = \mkw\store::getEm()->getConnection();

        $where = [\Services\KeszletService::getKeszletWhereSql((bool)$datum, (bool)$raktarid)];
        $params = [];
        if ($datum) {
            $params['fifodatum'] = $datum;
        }
        if ($raktarid) {
            $params['fiforaktar'] = (int)$raktarid;
        }
        if ($termekids) {
            $where[] = 'bt.termek_id IN (' . $this->intList($termekids) . ')';
        }
        if ($csoportok) {
            $where[] = '(' . implode(' OR ', array_map(
                fn($cs) => $this->groupWhere('bf.raktar_id', 'bt.termek_id', 'bt.termekvaltozat_id', $cs),
                $csoportok
            )) . ')';
        }
        $wheresql = implode(' AND ', $where);

        $potarak = $this->getFallbackPrices($wheresql, $params);

        $sql = 'SELECT COALESCE(bf.raktar_id, 0) AS raktarid, bt.termek_id AS termekid,'
            . ' COALESCE(bt.termekvaltozat_id, 0) AS valtozatid,'
            . ' bf.teljesites, bt.bizonylatfej_id AS fejid, bt.id AS tetelid, bf.irany AS fejirany,'
            . ' bt.mennyiseg * bt.irany AS mozgas, ' . $this->arSql() . ' AS ar'
            . ' FROM bizonylattetel bt'
            . ' INNER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE ' . $wheresql
            . ' ORDER BY raktarid, termekid, valtozatid, bf.teljesites,'
            . ' (bt.mennyiseg * bt.irany) DESC, bt.bizonylatfej_id, bt.id';

        $eredmeny = [];
        $kulcs = null;
        $sor = null;
        $retegek = [];
        $tartozas = 0;
        $utolsoAr = null;

        $st = $conn->executeQuery($sql, $params);
        while ($r = $st->fetchAssociative()) {
            $ujkulcs = $r['raktarid'] . '|' . $r['termekid'] . '|' . $r['valtozatid'];
            if ($ujkulcs !== $kulcs) {
                if ($kulcs !== null) {
                    $eredmeny[$kulcs] = $this->closeGroup($sor, $retegek, $tartozas);
                }
                $kulcs = $ujkulcs;
                $sor = [
                    'raktarid' => (int)$r['raktarid'] ?: null,
                    'termekid' => (int)$r['termekid'],
                    'valtozatid' => (int)$r['valtozatid'] ?: null,
                ];
                $retegek = [];
                $tartozas = 0;
                $utolsoAr = null;
            }

            $mozgas = (int)round((float)$r['mozgas'] * self::SKALA);
            if ($mozgas > 0) {
                [$ar, $becsult] = $this->retegAr($r, $utolsoAr, $potarak);
                if (!$becsult) {
                    $utolsoAr = $ar;
                }
                if ($tartozas > 0) {
                    $torleszt = min($tartozas, $mozgas);
                    $tartozas -= $torleszt;
                    $mozgas -= $torleszt;
                }
                if ($mozgas > 0) {
                    $retegek[] = [
                        'mennyiseg' => $mozgas,
                        'egysegar' => $ar,
                        'becsult' => $becsult,
                        'fejid' => $r['fejid'],
                        'tetelid' => (int)$r['tetelid'],
                        'teljesites' => $r['teljesites'],
                    ];
                }
            } elseif ($mozgas < 0) {
                $kell = -$mozgas;
                while (($kell > 0) && $retegek) {
                    $i = array_key_first($retegek);
                    if ($retegek[$i]['mennyiseg'] > $kell) {
                        $retegek[$i]['mennyiseg'] -= $kell;
                        $kell = 0;
                    } else {
                        $kell -= $retegek[$i]['mennyiseg'];
                        unset($retegek[$i]);
                    }
                }
                if ($kell > 0) {
                    $tartozas += $kell;
                }
            }
        }
        if ($kulcs !== null) {
            $eredmeny[$kulcs] = $this->closeGroup($sor, $retegek, $tartozas);
        }

        return $eredmeny;
    }

    /**
     * Egy réteg önköltsége. A sor saját ára csak akkor önköltség, ha valóban bevét
     * bizonylatról jön: a kiadó bizonylatra írt negatív tétel (visszavét) ára eladási ár,
     * garancialevélen gyakran nulla.
     *
     * @return array{0: float, 1: bool} ár és „becsült" jelzés
     */
    private function retegAr(array $r, $utolsoAr, array $potarak): array
    {
        $ar = (float)$r['ar'];
        if (($r['fejirany'] > 0) && ($ar > 0)) {
            return [$ar, false];
        }
        if ($utolsoAr !== null) {
            return [$utolsoAr, true];
        }
        $kulcs = $r['termekid'] . '|' . $r['valtozatid'];
        if (isset($potarak[$kulcs])) {
            return [$potarak[$kulcs], true];
        }
        return [0.0, true];
    }

    /**
     * A csoport zárása: a nyitott rétegekből mennyiség és érték, vagy – ha maradt
     * fedezetlen kiadás – előjeles hiány nulla értékkel. Kitalált áron nem értékelünk.
     */
    private function closeGroup(array $sor, array $retegek, int $tartozas): array
    {
        $mennyiseg = 0;
        $ertek = 0.0;
        $becsult = false;
        $megmaradt = [];
        foreach ($retegek as $reteg) {
            if ($reteg['mennyiseg'] <= 0) {
                continue;
            }
            $mennyiseg += $reteg['mennyiseg'];
            $ertek += ($reteg['mennyiseg'] / self::SKALA) * $reteg['egysegar'];
            $becsult = $becsult || $reteg['becsult'];
            $megmaradt[] = $reteg;
        }

        $db = $mennyiseg / self::SKALA;
        if ($tartozas > 0) {
            $db = -$tartozas / self::SKALA;
            $ertek = 0;
            $megmaradt = [];
        }

        return $sor + [
            'mennyiseg' => round($db, 2),
            'ertek' => round($ertek, 2),
            'egysegertek' => $db > 0 ? round($ertek / $db, 4) : null,
            'becsult' => $megmaradt ? $becsult : false,
            'retegdb' => count($megmaradt),
            'retegek' => $megmaradt,
        ];
    }

    /**
     * Az utolsó ismert, nem nulla beszerzési ár termékenként/változatonként – ez az
     * ársorrend utolsó előtti lépcsője. Kulcsa a (termék, változat) pár, nem csak a
     * változat: így a változat nélküli termék is kap árat.
     */
    private function getFallbackPrices(string $wheresql, array $params): array
    {
        $ar = $this->arSql();
        $sql = 'SELECT x.termekid, x.valtozatid, x.ertek FROM ('
            . 'SELECT bt.termek_id AS termekid, COALESCE(bt.termekvaltozat_id, 0) AS valtozatid,'
            . ' ' . $ar . ' AS ertek,'
            . ' ROW_NUMBER() OVER (PARTITION BY bt.termek_id, COALESCE(bt.termekvaltozat_id, 0)'
            . ' ORDER BY bf.teljesites DESC, bf.id DESC) AS rn'
            . ' FROM bizonylattetel bt'
            . ' INNER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE ' . $wheresql . ' AND bf.irany > 0 AND ' . $ar . ' > 0'
            . ') x WHERE x.rn = 1';

        $map = [];
        $st = \mkw\store::getEm()->getConnection()->executeQuery($sql, $params);
        while ($r = $st->fetchAssociative()) {
            $map[$r['termekid'] . '|' . $r['valtozatid']] = (float)$r['ertek'];
        }
        return $map;
    }

    /** HUF nettó egységár a bizonylattételen – a házi képlet, árfolyamos tartalékkal. */
    private function arSql(): string
    {
        return 'IF(COALESCE(bt.nettoegysarhuf, 0) = 0, bt.nettoegysar * bf.arfolyam, bt.nettoegysarhuf)';
    }

    // ------------------------------------------------------------------
    // tárolás
    // ------------------------------------------------------------------

    /**
     * A számítás eredményének kiírása. Rövid tranzakcióban: a menet memóriában futott le,
     * itt már csak néhány tízezer sor cserélődik.
     *
     * TRUNCATE nem használható: implicit commitot csinál, és idegen kulccsal hivatkozott
     * táblán nem is megy.
     */
    private function store(array $eredmeny, bool $mind, array $csoportok): void
    {
        $conn = \mkw\store::getEm()->getConnection();
        $conn->beginTransaction();
        try {
            if ($mind) {
                $conn->executeStatement('DELETE FROM fiforeteg');
                $conn->executeStatement('DELETE FROM fifoertek');
            } else {
                foreach (array_chunk($csoportok, 200) as $koteg) {
                    $felt = '(' . implode(' OR ', array_map(
                        fn($cs) => $this->groupWhere('raktar_id', 'termek_id', 'termekvaltozat_id', $cs),
                        $koteg
                    )) . ')';
                    $conn->executeStatement('DELETE FROM fiforeteg WHERE ' . $felt);
                    $conn->executeStatement('DELETE FROM fifoertek WHERE ' . $felt);
                }
            }

            $most = date('Y-m-d H:i:s');
            $ertekek = [];
            $retegek = [];
            foreach ($eredmeny as $sor) {
                $ertekek[] = [
                    $sor['raktarid'],
                    $sor['termekid'],
                    $sor['valtozatid'],
                    $sor['mennyiseg'],
                    $sor['ertek'],
                    $sor['egysegertek'],
                    $sor['becsult'] ? 1 : 0,
                    $sor['retegdb'],
                    $most,
                ];
                foreach ($sor['retegek'] as $reteg) {
                    $retegek[] = [
                        $sor['raktarid'],
                        $sor['termekid'],
                        $sor['valtozatid'],
                        $reteg['fejid'],
                        $reteg['tetelid'],
                        $reteg['teljesites'],
                        $reteg['mennyiseg'] / self::SKALA,
                        $reteg['egysegar'],
                        $reteg['becsult'] ? 1 : 0,
                    ];
                }
            }

            $this->bulkInsert(
                'fifoertek',
                ['raktar_id', 'termek_id', 'termekvaltozat_id', 'mennyiseg', 'ertek', 'egysegertek', 'becsult', 'retegdb', 'szamitva'],
                $ertekek
            );
            $this->bulkInsert(
                'fiforeteg',
                ['raktar_id', 'termek_id', 'termekvaltozat_id', 'bebizonylatfej_id', 'bebizonylattetel_id', 'teljesites', 'mennyiseg', 'egysegar', 'becsult'],
                $retegek
            );

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
        self::clearCache();
    }

    private function bulkInsert(string $tabla, array $mezok, array $sorok): void
    {
        if (!$sorok) {
            return;
        }
        $conn = \mkw\store::getEm()->getConnection();
        $helyek = '(' . implode(',', array_fill(0, count($mezok), '?')) . ')';
        foreach (array_chunk($sorok, self::INSERTKOTEG) as $koteg) {
            $ertekek = [];
            foreach ($koteg as $sor) {
                foreach ($sor as $v) {
                    $ertekek[] = $v;
                }
            }
            $conn->executeStatement(
                'INSERT INTO ' . $tabla . ' (' . implode(',', $mezok) . ') VALUES '
                . implode(',', array_fill(0, count($koteg), $helyek)),
                $ertekek
            );
        }
    }

    // ------------------------------------------------------------------
    // piszkos csoportok
    // ------------------------------------------------------------------

    private function getValtozasMaxId()
    {
        return (int)\mkw\store::getEm()->getConnection()->fetchOne('SELECT MAX(id) FROM fifovaltozas');
    }

    /** @return array [raktarid, termekid, valtozatid] hármasok, a 0-k null-lá fordítva */
    private function getValtozasGroups(int $maxid): array
    {
        $sorok = \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT DISTINCT raktar_id, termek_id, termekvaltozat_id FROM fifovaltozas WHERE id <= ?',
            [$maxid]
        );
        $ret = [];
        foreach ($sorok as $sor) {
            $ret[] = [
                (int)$sor['raktar_id'] ?: null,
                (int)$sor['termek_id'],
                (int)$sor['termekvaltozat_id'] ?: null,
            ];
        }
        return $ret;
    }

    private function clearValtozas(int $maxid): void
    {
        if ($maxid) {
            \mkw\store::getEm()->getConnection()->executeStatement('DELETE FROM fifovaltozas WHERE id <= ?', [$maxid]);
        }
    }

    /** Egy termék minden készletcsoportja, a bizonylattételekből. */
    private function getTermekGroups(int $termekid): array
    {
        $sorok = \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT DISTINCT bf.raktar_id, bt.termekvaltozat_id'
            . ' FROM bizonylattetel bt INNER JOIN bizonylatfej bf ON (bt.bizonylatfej_id = bf.id)'
            . ' WHERE bt.termek_id = ?',
            [$termekid]
        );
        $ret = [];
        foreach ($sorok as $sor) {
            $ret[] = [(int)$sor['raktar_id'] ?: null, $termekid, (int)$sor['termekvaltozat_id'] ?: null];
        }
        return $ret;
    }

    // ------------------------------------------------------------------
    // listákhoz: előtöltés
    // ------------------------------------------------------------------

    /**
     * A tárolt FIFO értékek betöltése egy listaoldal termékeire, két lekérdezéssel –
     * a `KeszletService::preloadStock()` mintájára, hogy a soronkénti olvasás már csak
     * a memóriából dolgozzon.
     */
    public static function preloadErtek(array $termekids, array $valtozatids): void
    {
        $conn = \mkw\store::getEm()->getConnection();
        $termekids = array_values(array_unique(array_map('intval', array_filter($termekids))));
        $valtozatids = array_values(array_unique(array_map('intval', array_filter($valtozatids))));

        if ($termekids) {
            $sql = 'SELECT termek_id AS entityid, SUM(mennyiseg) AS mennyiseg, SUM(ertek) AS ertek,'
                . ' MAX(becsult) AS becsult, MIN(szamitva) AS szamitva'
                . ' FROM fifoertek WHERE termekvaltozat_id IS NULL AND termek_id IN ('
                . implode(',', $termekids) . ') GROUP BY termek_id';
            self::cacheSorok('t', $termekids, $conn->fetchAllAssociative($sql));
        }
        if ($valtozatids) {
            $sql = 'SELECT termekvaltozat_id AS entityid, SUM(mennyiseg) AS mennyiseg, SUM(ertek) AS ertek,'
                . ' MAX(becsult) AS becsult, MIN(szamitva) AS szamitva'
                . ' FROM fifoertek WHERE termekvaltozat_id IN ('
                . implode(',', $valtozatids) . ') GROUP BY termekvaltozat_id';
            self::cacheSorok('v', $valtozatids, $conn->fetchAllAssociative($sql));
        }
    }

    private static function cacheSorok(string $tipus, array $ids, array $sorok): void
    {
        foreach ($ids as $id) {
            self::$ertekCache[$tipus . $id] = null;
        }
        foreach ($sorok as $sor) {
            self::$ertekCache[$tipus . (int)$sor['entityid']] = [
                'mennyiseg' => $sor['mennyiseg'],
                'ertek' => $sor['ertek'],
                'becsult' => (bool)$sor['becsult'],
                'szamitva' => $sor['szamitva'],
            ];
        }
    }

    /**
     * Egy termék vagy változat összevont (minden raktáras) FIFO értéke. A terméklistán
     * nincs raktárszűrő, ezért – ahogy a készlet oszlop is – összevont értéket mutatunk.
     *
     * @param \Entities\Termek|\Entities\TermekValtozat $entity
     */
    public static function getErtek($entity): ?array
    {
        if (!$entity || !$entity->getId()) {
            return null;
        }
        $tipus = ($entity instanceof TermekValtozat) ? 'v' : 't';
        $kulcs = $tipus . $entity->getId();
        if (!array_key_exists($kulcs, self::$ertekCache)) {
            if ($tipus === 'v') {
                self::preloadErtek([], [$entity->getId()]);
            } else {
                self::preloadErtek([$entity->getId()], []);
            }
        }
        return self::$ertekCache[$kulcs] ?? null;
    }

    public static function clearCache(): void
    {
        self::$ertekCache = [];
    }

    // ------------------------------------------------------------------
    // segédek
    // ------------------------------------------------------------------

    /**
     * A számítás és a kézi indítás nem érhet össze. Nulla várakozású MySQL zár: ha fut egy
     * menet, a második azonnal kilép. A kapcsolat elvesztésekor a zár magától elenged, így
     * egy megölt futás nem ragad be.
     */
    private function withLock(callable $mit)
    {
        $conn = \mkw\store::getEm()->getConnection();
        $nev = 'mkwfifo_' . \mkw\store::getConfigValue('db.dbname');
        if (!$conn->fetchOne('SELECT GET_LOCK(?, 0)', [$nev])) {
            throw new \Exception(t('Éppen fut egy FIFO számítás.'));
        }
        try {
            return $mit();
        } finally {
            $conn->executeStatement('SELECT RELEASE_LOCK(?)', [$nev]);
        }
    }

    /** NULL-biztos csoportfeltétel: a változat nélküli termék is önálló csoport. */
    private function groupWhere(string $raktarmezo, string $termekmezo, string $valtozatmezo, array $csoport): string
    {
        [$raktarid, $termekid, $valtozatid] = $csoport;
        return '(' . $raktarmezo . ' <=> ' . ($raktarid ? (int)$raktarid : 'NULL')
            . ' AND ' . $termekmezo . ' <=> ' . (int)$termekid
            . ' AND ' . $valtozatmezo . ' <=> ' . ($valtozatid ? (int)$valtozatid : 'NULL') . ')';
    }

    private function intList(array $ids): string
    {
        $ids = array_values(array_unique(array_map('intval', array_filter($ids))));
        return $ids ? implode(',', $ids) : '0';
    }

    private function report(array $eredmeny, int $csoportdb): array
    {
        $reteg = 0;
        $becsult = 0;
        $fedezetlen = 0;
        $ertek = 0;
        foreach ($eredmeny as $sor) {
            $reteg += $sor['retegdb'];
            $ertek += $sor['ertek'];
            if ($sor['becsult']) {
                $becsult++;
            }
            if ($sor['mennyiseg'] < 0) {
                $fedezetlen++;
            }
        }
        return [
            'csoport' => $csoportdb,
            'szamolt' => count($eredmeny),
            'reteg' => $reteg,
            'becsult' => $becsult,
            'fedezetlen' => $fedezetlen,
            'ertek' => round($ertek, 2),
        ];
    }

}
