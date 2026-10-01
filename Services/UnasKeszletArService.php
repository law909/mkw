<?php

namespace Services;

use Entities\Termek;
use Entities\TermekAr;
use Entities\TermekValtozat;

/**
 * Készlet (`setStock`) és ár (`setProduct`) feltöltés az UNAS-ba a párosított termékekre és
 * változatokra (`Termek.unasid` / `TermekValtozat.unasid`).
 *
 * Nincs változásfigyelő listener: a készlet számított érték, ami kód nélkül is változik (jövőbeli
 * teljesítés, akció vége), ezért minden futás mindent újraszámol, és az `unastermekszinkron`
 * táblában tárolt, legutóbb kiküldött értékkel összevetve csak az eltérést küldi.
 * Lásd docs/unas-termek-visszairas.md (5. és 7. pont).
 */
class UnasKeszletArService
{

    /** ≤100 termékes hívásnál él a bő (1000/óra) keret */
    public const KOTEGMERET = 100;

    /** ennyi egymás utáni sikertelen küldés után a tétel csak teljes futással megy újra */
    public const MAXHIBA = 5;

    private const MINTA = 20;

    /** @var UnasService */
    private $unas;

    public function __construct(?UnasService $unas = null)
    {
        $this->unas = $unas ?: new UnasService();
    }

    public static function isKeszletEnabled()
    {
        return (bool)\mkw\store::getParameter(\mkw\consts::UnasKeszletFeltoltes);
    }

    public static function isArEnabled()
    {
        return (bool)\mkw\store::getParameter(\mkw\consts::UnasArFeltoltes);
    }

    /** Az akciós ár csak ársávos telepítésen, kiválasztott akciós ársávval megy – különben az UNAS-é. */
    public static function isAkcioEnabled()
    {
        return \mkw\store::isArsavok() && (int)\mkw\store::getParameter(\mkw\consts::UnasAkciosArsav) > 0;
    }

    /**
     * Szándékosan nem a rendelés-import raktára (UnasRaktar): oda csak az UNAS rendelések kerülnek,
     * a valódi készlet máshol van. Üresen az UNAS webshopjában látható raktárak – null, ha mind.
     *
     * @return int|int[]|null
     */
    public static function getKeszletRaktar()
    {
        $raktarid = (int)\mkw\store::getParameter(\mkw\consts::UnasKeszletRaktar);
        if ($raktarid) {
            return $raktarid;
        }
        return KeszletService::getWebshopRaktarIds((int)\mkw\store::getParameter(\mkw\consts::UnasWebshopnum) ?: null);
    }

    /**
     * @param array $opts `szaraz` – csak számol, nem küld; `teljes` – a legutóbb kiküldött értéktől
     *                    és a hibaszámlálótól függetlenül mindent küld; `keszlet`/`ar` – felülírja a
     *                    beállítás szerinti kapcsolót; `unasid` – csak ezek az UNAS termékek (tömb vagy vesszős lista)
     *
     * @return array a riport
     */
    public function sync(array $opts = [])
    {
        $keszletBe = (bool)($opts['keszlet'] ?? self::isKeszletEnabled());
        $arBe = (bool)($opts['ar'] ?? self::isArEnabled());
        $report = $this->emptyReport(!empty($opts['szaraz']), $keszletBe, $arBe);
        if (!UnasService::isEnabled() || (!$keszletBe && !$arBe)) {
            return $report;
        }
        $teljes = !empty($opts['teljes']);

        $celok = $this->loadTargets($report);
        if (!empty($opts['unasid'])) {
            $celok = $this->filterTargets($celok, $opts['unasid'], $report);
        }
        $report['celok'] = count($celok);
        $valtozatos = $this->loadUnasValtozatosTermekIds();
        $raktarid = self::getKeszletRaktar();

        $em = \mkw\store::getEm();
        foreach (array_chunk($celok, self::KOTEGMERET) as $koteg) {
            $tetelek = $this->calcKoteg($koteg, $raktarid, $valtozatos, $keszletBe, $arBe, $report);
            $state = $this->loadState(array_column($koteg, 'unasid'));

            $keszletKuldendo = [];
            $arKuldendo = [];
            foreach ($tetelek as $unasid => $t) {
                $s = $state[$unasid] ?? null;
                if (!$teljes && $s && (int)$s['hibadb'] >= self::MAXHIBA) {
                    $report['kihagyva_hiba']++;
                    continue;
                }
                if (array_key_exists('keszlet', $t) && ($teljes || !$s || !$this->egyezik($s['keszlet'], $t['keszlet']))) {
                    $keszletKuldendo[$unasid] = $t;
                }
                $ar = array_key_exists('brutto', $t) ? $this->arKuldendo($t, $s, $teljes) : null;
                if ($ar) {
                    $arKuldendo[$unasid] = $ar;
                }
            }
            $report['keszlet']['valtozott'] += count($keszletKuldendo);
            $report['ar']['valtozott'] += count($arKuldendo);
            $this->addMinta($report, $keszletKuldendo, $arKuldendo, $state);

            if (!$report['szaraz']) {
                if ($keszletKuldendo && !$this->sendKeszlet($keszletKuldendo, $report)) {
                    break;
                }
                if ($arKuldendo && !$this->sendAr($arKuldendo, $report)) {
                    break;
                }
            }

            if (!$em->isOpen()) {
                break;
            }
            // a kötegek nem hivatkoznak egymás entitásaira; enélkül a teljes katalógus a memóriában maradna
            $em->clear();
            KeszletService::clearKeszletCache();
        }

        if ($report['keszlet']['hiba'] || $report['ar']['hiba'] || $report['hivashiba'] !== '') {
            $this->unas->logApiError($this->hibaUzenet($report));
        }
        return $report;
    }

    // ------------------------------------------------------------------
    // Célok és számítás
    // ------------------------------------------------------------------

    /**
     * @return array<int, array{unasid: string, termekid: int, valtozatid: int|null}>
     */
    private function loadTargets(array &$report)
    {
        $sorok = \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            "SELECT TRIM(t.unasid) AS unasid, t.id AS termekid, NULL AS valtozatid FROM termek t WHERE TRIM(t.unasid) <> ''"
            . " UNION ALL"
            . " SELECT TRIM(v.unasid), v.termek_id, v.id FROM termekvaltozat v WHERE TRIM(v.unasid) <> ''"
            . " ORDER BY termekid, valtozatid"
        );
        $celok = [];
        foreach ($sorok as $sor) {
            if (isset($celok[$sor['unasid']])) {
                $report['duplikalt']++;
                continue;
            }
            $celok[$sor['unasid']] = [
                'unasid' => $sor['unasid'],
                'termekid' => (int)$sor['termekid'],
                'valtozatid' => $sor['valtozatid'] ? (int)$sor['valtozatid'] : null,
            ];
        }
        return array_values($celok);
    }

    /** Próbafutáshoz: csak a megadott UNAS termékek. A nem párosított azonosítót a riport jelzi. */
    private function filterTargets(array $celok, $unasids, array &$report)
    {
        $kert = is_array($unasids) ? $unasids : explode(',', (string)$unasids);
        $kert = array_values(array_unique(array_filter(array_map('trim', $kert), static fn($v) => $v !== '')));
        $celok = array_values(array_filter($celok, static fn($c) => in_array($c['unasid'], $kert, true)));
        $report['ismeretlen_unasid'] = array_values(array_diff($kert, array_column($celok, 'unasid')));
        return $celok;
    }

    /**
     * Az UNAS-változatos termékek (`unasvaltozat` kitöltve): ott a készlet változat-kombinációnként
     * menne, `Variants` blokkal – az még nincs megírva, és egyetlen Qty-vel felülírnánk.
     *
     * @return array<int, true>
     */
    private function loadUnasValtozatosTermekIds()
    {
        $ids = \mkw\store::getEm()->getConnection()->fetchFirstColumn(
            "SELECT DISTINCT termek_id FROM termekvaltozat WHERE TRIM(unasvaltozat) <> ''"
        );
        return array_fill_keys(array_map('intval', $ids), true);
    }

    /**
     * @return array<string, array> unasid => ['termekid', 'valtozatid', 'keszlet'?, 'netto'?, 'brutto'?]
     */
    private function calcKoteg(array $koteg, $raktarid, array $valtozatos, $keszletBe, $arBe, array &$report)
    {
        $termekids = array_values(array_unique(array_column($koteg, 'termekid')));
        $valtozatids = array_values(array_filter(array_column($koteg, 'valtozatid')));
        $termekek = $this->loadEntities(Termek::class, $termekids);
        $valtozatok = $this->loadEntities(TermekValtozat::class, $valtozatids);

        if ($keszletBe) {
            $termekCelids = array_column(array_filter($koteg, static fn($c) => !$c['valtozatid']), 'termekid');
            KeszletService::preloadStock($termekCelids, $valtozatids, null, $raktarid);
            if (!is_array($raktarid)) {
                KeszletService::preload($termekids, $valtozatids, $raktarid);
            }
        }
        $arak = $arBe ? $this->loadArak($termekek) : [];
        $akcioBe = $arBe && self::isAkcioEnabled();

        $result = [];
        foreach ($koteg as $cel) {
            /** @var Termek|null $termek */
            $termek = $termekek[$cel['termekid']] ?? null;
            /** @var TermekValtozat|null $valtozat */
            $valtozat = $cel['valtozatid'] ? ($valtozatok[$cel['valtozatid']] ?? null) : null;
            if (!$termek || ($cel['valtozatid'] && !$valtozat)) {
                continue;
            }
            $t = ['termekid' => $cel['termekid'], 'valtozatid' => $cel['valtozatid']];

            if ($keszletBe) {
                if (!$termek->getMozgat()) {
                    $report['keszlet']['nemkeszletes']++;
                } elseif (!$valtozat && isset($valtozatos[$cel['termekid']])) {
                    $report['keszlet']['unasvaltozatos']++;
                } else {
                    $t['keszlet'] = $this->calcKeszlet($termek, $valtozat, $raktarid);
                }
            }

            if ($arBe) {
                $ar = $this->calcAr($termek, $valtozat, $arak);
                if ($ar) {
                    $t['netto'] = $ar['netto'];
                    $t['brutto'] = $ar['brutto'];
                    if ($akcioBe) {
                        $t['akcio'] = $this->calcAkcio($termek, $arak, $ar['brutto']);
                    }
                } else {
                    $report['ar']['nincsar']++;
                }
            }
            $result[$cel['unasid']] = $t;
        }
        return $result;
    }

    /**
     * A KeszletService::calcAvailableStock() képlete: készlet − foglalás − min. bolti készlet, nullára
     * vágva. A foglalás levonása kell, mert az UNAS a rendeléskor már csökkentette a saját készletét.
     * Raktárlistánál a min. készlet létrája raktár nélkül megy, az csak egy raktárt ismer.
     */
    private function calcKeszlet(Termek $termek, ?TermekValtozat $valtozat, $raktarid)
    {
        $o = $valtozat ?: $termek;
        $keszlet = KeszletService::getKeszlet($o, null, $raktarid)
            - KeszletService::getFoglaltMennyiseg($o, null, null, $raktarid)
            - KeszletService::getMinKeszlet($termek, $valtozat, is_array($raktarid) ? null : $raktarid);
        return round(max((float)$keszlet, 0), 4);
    }

    /**
     * @param array|null $s az állapotsor
     *
     * @return array|null a tétel `akciokuld`/`akcioregi` kulccsal, vagy null, ha nincs mit küldeni
     */
    private function arKuldendo(array $t, ?array $s, $teljes)
    {
        $normal = $teljes || !$s || $s['arkuldve'] === null
            || !$this->egyezik($s['netto'], $t['netto'], 2) || !$this->egyezik($s['brutto'], $t['brutto'], 2);
        $t['akcioregi'] = $s && $s['akciosbrutto'] !== null
            ? ['netto' => (float)$s['akciosnetto'], 'brutto' => (float)$s['akciosbrutto']]
            : null;
        if (!array_key_exists('akcio', $t)) {
            $t['akciokuld'] = false;
        } elseif ($t['akcio']) {
            $t['akciokuld'] = $teljes || !$s || $s['akcioskuldve'] === null || !$this->egyezikAkcio($s, $t['akcio']);
        } else {
            // csak a mi kiküldött akciónkat tesszük lejárttá, az UNAS-ban kézzel felvitthez nem nyúlunk
            $t['akciokuld'] = $t['akcioregi'] !== null;
        }
        return $normal || $t['akciokuld'] ? $t : null;
    }

    private function loadEntities($class, array $ids)
    {
        if (!$ids) {
            return [];
        }
        $rows = \mkw\store::getEm()->createQuery('SELECT x FROM ' . $class . ' x WHERE x.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getResult();
        $result = [];
        foreach ($rows as $row) {
            $result[$row->getId()] = $row;
        }
        return $result;
    }

    /**
     * Ársávos telepítésen a beállított (vagy az alapértelmezett) ársáv sora – szándékosan NEM az
     * akciós sávok láncán át: az UNAS normál árába az alapár való, az akciós a sajátjába.
     *
     * @return array{normal: array<int, TermekAr>, akcios: array<int, TermekAr>}
     */
    private function loadArak(array $termekek)
    {
        $result = ['normal' => [], 'akcios' => []];
        if (!\mkw\store::isArsavok() || !$termekek) {
            return $result;
        }
        $repo = \mkw\store::getEm()->getRepository(TermekAr::class);
        $valutanem = \mkw\store::getParameter(\mkw\consts::UnasValutanem) ?: null;
        $arsav = \mkw\store::getParameter(\mkw\consts::UnasArsav) ?: \mkw\store::getParameter(\mkw\consts::Arsav);
        $result['normal'] = $repo->getArsavArByTermek(array_keys($termekek), $valutanem, $arsav ?: null);
        if (self::isAkcioEnabled()) {
            $result['akcios'] = $repo->getArsavArByTermek(
                array_keys($termekek),
                $valutanem,
                \mkw\store::getParameter(\mkw\consts::UnasAkciosArsav)
            );
        }
        return $result;
    }

    /**
     * Az akció nélküli normál ár; ársáv nélkül a változat felára is hozzáadódik, mint a
     * Termek::getKedvezmenynelkuliNettoAr()-ban.
     *
     * @return array{netto: float, brutto: float}|null null, ha nincs ár
     */
    private function calcAr(Termek $termek, ?TermekValtozat $valtozat, array $arak)
    {
        if (\mkw\store::isArsavok()) {
            $ar = $arak['normal'][$termek->getId()] ?? null;
            $netto = $ar ? (float)$ar->getNetto() : 0.0;
            $brutto = $ar ? (float)$ar->getBrutto() : 0.0;
        } else {
            $netto = (float)$termek->getNetto() + ($valtozat ? (float)$valtozat->getNetto() : 0);
            $brutto = (float)$termek->getBrutto() + ($valtozat ? (float)$valtozat->getBrutto() : 0);
        }
        if ($brutto <= 0) {
            return null;
        }
        return ['netto' => round($netto, 2), 'brutto' => round($brutto, 2)];
    }

    /**
     * Az akciós ársáv ára, ha kisebb a normálnál – az akciós sávban sokszor a normál ár másolata áll.
     *
     * @return array{netto: float, brutto: float}|null null, ha nincs akció
     */
    private function calcAkcio(Termek $termek, array $arak, $normalBrutto)
    {
        $ar = $arak['akcios'][$termek->getId()] ?? null;
        if (!$ar || (float)$ar->getBrutto() <= 0 || round((float)$ar->getBrutto(), 2) >= $normalBrutto) {
            return null;
        }
        return ['netto' => round((float)$ar->getNetto(), 2), 'brutto' => round((float)$ar->getBrutto(), 2)];
    }

    // ------------------------------------------------------------------
    // Küldés
    // ------------------------------------------------------------------

    /** @return bool folytatható-e a menet */
    private function sendKeszlet(array $tetelek, array &$report)
    {
        $products = [];
        foreach ($tetelek as $unasid => $t) {
            // modify: abszolút mennyiség, tehát egy megismételt hívás sem viszi el duplán a készletet
            $products[] = [
                'Id' => $unasid,
                'Action' => 'modify',
                'Stocks' => ['Stock' => ['Qty' => $this->formatSzam($t['keszlet'])]],
            ];
        }
        $api = $this->unas->getApi();
        $report['hivasok']++;
        $xml = $api->setStock(['Product' => $products]);
        if (!$xml) {
            return $this->hivasHiba($api, 'setStock', $report);
        }
        $now = date('Y-m-d H:i:s');
        foreach ($this->itemResults($xml, array_keys($tetelek), $report) as $unasid => $hiba) {
            $t = $tetelek[$unasid];
            if ($hiba === '') {
                $report['keszlet']['kuldve']++;
                $this->saveState($unasid, $t, ['keszlet' => $t['keszlet'], 'keszletkuldve' => $now]);
            } else {
                $report['keszlet']['hiba']++;
                $this->saveHiba($unasid, $t, 'setStock: ' . $hiba, $report);
            }
        }
        return true;
    }

    /** @return bool folytatható-e a menet */
    private function sendAr(array $tetelek, array &$report)
    {
        $products = [];
        foreach ($tetelek as $unasid => $t) {
            $prices = [[
                'Type' => 'normal',
                'Net' => $this->formatSzam($t['netto']),
                'Gross' => $this->formatSzam($t['brutto']),
            ]];
            // akciókapcsoló nélkül a sale ár az UNAS-ban marad, ahol beállították
            if ($t['akciokuld']) {
                $prices[] = $this->salePrice($t);
            }
            $products[] = [
                'Id' => $unasid,
                'Action' => 'modify',
                'Prices' => ['Price' => $prices],
            ];
        }
        $api = $this->unas->getApi();
        $report['hivasok']++;
        $xml = $api->setProduct(['Product' => $products]);
        if (!$xml) {
            return $this->hivasHiba($api, 'setProduct', $report);
        }
        $now = date('Y-m-d H:i:s');
        foreach ($this->itemResults($xml, array_keys($tetelek), $report) as $unasid => $hiba) {
            $t = $tetelek[$unasid];
            if ($hiba === '') {
                $report['ar']['kuldve']++;
                $mezok = ['netto' => $t['netto'], 'brutto' => $t['brutto'], 'arkuldve' => $now];
                if ($t['akciokuld']) {
                    $report['ar'][$t['akcio'] ? 'akcios' : 'akciolejarat']++;
                    $mezok += [
                        'akciosnetto' => $t['akcio']['netto'] ?? null,
                        'akciosbrutto' => $t['akcio']['brutto'] ?? null,
                        'akcioskuldve' => $now,
                    ];
                }
                $this->saveState($unasid, $t, $mezok);
            } else {
                $report['ar']['hiba']++;
                $this->saveHiba($unasid, $t, 'setProduct: ' . $hiba, $report);
            }
        }
        return true;
    }

    /**
     * Az UNAS-ban akciós árat törölni nem lehet, csak lejárttá tenni: a megszűnt (általunk kiküldött)
     * akciót a legutóbbi akciós árral és tegnapi lejárattal küldjük. Az ársávos akciónak nincs dátuma, ezért ma kezdődik.
     */
    private function salePrice(array $t)
    {
        if ($t['akcio']) {
            return [
                'Type' => 'sale',
                'Net' => $this->formatSzam($t['akcio']['netto']),
                'Gross' => $this->formatSzam($t['akcio']['brutto']),
                'Start' => date('Y.m.d'),
            ];
        }
        return [
            'Type' => 'sale',
            'Net' => $this->formatSzam($t['akcioregi']['netto']),
            'Gross' => $this->formatSzam($t['akcioregi']['brutto']),
            'Start' => date('Y.m.d', strtotime('-2 days')),
            'End' => date('Y.m.d', strtotime('-1 day')),
        ];
    }

    /**
     * Az egész hívás elbukott. A menet mindenképp megáll: ha a hiba jogosultsági, a többi köteg is
     * elbukna, és 20 egymás utáni hibás hívás egy órára kizárja az IP-t.
     *
     * @return false
     */
    private function hivasHiba($api, $endpoint, array &$report)
    {
        $report['fek'] = true;
        $report['hivashiba'] = $endpoint . ': ' . $api->getLasterrorsAsString();
        if (array_intersect(array_column($api->getLasterrors(), 'code'), ['RATELIMIT', 'MAINTENANCE', 'NOTCONFIGURED'])) {
            // el sem indult a hívás
            $report['hivasok']--;
        }
        return false;
    }

    /**
     * A válasz `Product` node-jai tételenként: `Id` szerint párosítva, ennek hiányában sorrendben.
     * A gyökérszintű hibát az UnasAPI már kiszűrte; a beágyazottat itt kell elkapni.
     *
     * @return array<string, string> unasid => hibaüzenet ('' ha rendben)
     */
    private function itemResults(\SimpleXMLElement $xml, array $unasids, array &$report)
    {
        $nodes = [];
        if ($xml->getName() === 'Product') {
            $nodes[] = $xml;
        } elseif (isset($xml->Product)) {
            foreach ($xml->Product as $node) {
                $nodes[] = $node;
            }
        }
        if (!$nodes) {
            // tételes válasz nélkül, de hiba nélkül: elfogadjuk, de a riportban látsszon
            $report['valasz_tetel_nelkul']++;
            return array_fill_keys($unasids, '');
        }

        $result = [];
        foreach ($nodes as $i => $node) {
            $id = isset($node->Id) ? trim((string)$node->Id) : '';
            if ($id === '' || !in_array($id, $unasids, true)) {
                $id = $unasids[$i] ?? '';
            }
            if ($id === '' || isset($result[$id])) {
                continue;
            }
            $result[$id] = $this->nodeError($node);
        }
        foreach ($unasids as $unasid) {
            if (!isset($result[$unasid])) {
                $result[$unasid] = 'a válaszban nincs ilyen termék';
            }
        }
        return $result;
    }

    private function nodeError(\SimpleXMLElement $node)
    {
        if (isset($node->Error)) {
            $code = isset($node->Error->Code) ? trim((string)$node->Error->Code) : '';
            $message = isset($node->Error->Message) ? trim((string)$node->Error->Message) : trim((string)$node->Error);
            return trim($code . ' ' . $message) ?: 'ERROR';
        }
        $statusz = isset($node->Status) ? strtolower(trim((string)$node->Status)) : '';
        if ($statusz !== '' && $statusz !== 'ok' && $statusz !== 'success') {
            return 'elutasítva: ' . trim((string)$node->Status);
        }
        return '';
    }

    // ------------------------------------------------------------------
    // Állapot – nyers DBAL, lásd Entities\Unastermekszinkron
    // ------------------------------------------------------------------

    /** @return array<string, array> unasid => sor */
    private function loadState(array $unasids)
    {
        if (!$unasids) {
            return [];
        }
        $rows = \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT unasid, keszlet, netto, brutto, arkuldve, akciosnetto, akciosbrutto, akcioskuldve, hibadb'
            . ' FROM unastermekszinkron WHERE unasid IN (?)',
            [$unasids],
            [\Doctrine\DBAL\ArrayParameterType::STRING]
        );
        return array_column($rows, null, 'unasid');
    }

    private function saveState($unasid, array $t, array $mezok)
    {
        $this->ensureRow($unasid, $t);
        $mezok += ['hibadb' => 0, 'hiba' => null, 'hibaido' => null];
        \mkw\store::getEm()->getConnection()->update('unastermekszinkron', $mezok, ['unasid' => $unasid]);
    }

    private function saveHiba($unasid, array $t, $hiba, array &$report)
    {
        $this->ensureRow($unasid, $t);
        \mkw\store::getEm()->getConnection()->executeStatement(
            'UPDATE unastermekszinkron SET hibadb = hibadb + 1, hiba = ?, hibaido = ? WHERE unasid = ?',
            [mb_substr($hiba, 0, 2000), date('Y-m-d H:i:s'), $unasid]
        );
        if (count($report['hibak']) < self::MINTA) {
            $report['hibak'][] = ['unasid' => $unasid, 'hiba' => $hiba];
        }
    }

    private function ensureRow($unasid, array $t)
    {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'INSERT INTO unastermekszinkron (unasid, termek_id, termekvaltozat_id, hibadb) VALUES (?, ?, ?, 0)'
            . ' ON DUPLICATE KEY UPDATE termek_id = VALUES(termek_id), termekvaltozat_id = VALUES(termekvaltozat_id)',
            [$unasid, $t['termekid'], $t['valtozatid']]
        );
    }

    // ------------------------------------------------------------------
    // Segédek
    // ------------------------------------------------------------------

    private function egyezik($regi, $uj, $tizedes = 4)
    {
        return $regi !== null && round((float)$regi, $tizedes) === round((float)$uj, $tizedes);
    }

    private function egyezikAkcio(array $s, ?array $akcio)
    {
        if (!$akcio) {
            return $s['akciosbrutto'] === null;
        }
        return $this->egyezik($s['akciosnetto'], $akcio['netto'], 2) && $this->egyezik($s['akciosbrutto'], $akcio['brutto'], 2);
    }

    /** Tizedespont, felesleges nullák nélkül: 12.5000 → 12.5, -0 → 0 */
    private function formatSzam($n)
    {
        $s = rtrim(rtrim(number_format((float)$n, 4, '.', ''), '0'), '.');
        return $s === '-0' || $s === '' ? '0' : $s;
    }

    private function addMinta(array &$report, array $keszlet, array $ar, array $state)
    {
        foreach ($keszlet as $unasid => $t) {
            if (count($report['minta']) >= self::MINTA) {
                return;
            }
            $report['minta'][] = ['unasid' => $unasid, 'mezo' => 'keszlet', 'regi' => $state[$unasid]['keszlet'] ?? null, 'uj' => $t['keszlet']];
        }
        foreach ($ar as $unasid => $t) {
            if (count($report['minta']) >= self::MINTA) {
                return;
            }
            $report['minta'][] = ['unasid' => $unasid, 'mezo' => 'brutto', 'regi' => $state[$unasid]['brutto'] ?? null, 'uj' => $t['brutto']];
            if ($t['akciokuld'] && count($report['minta']) < self::MINTA) {
                $report['minta'][] = [
                    'unasid' => $unasid,
                    'mezo' => 'akciós bruttó',
                    'regi' => $state[$unasid]['akciosbrutto'] ?? null,
                    'uj' => $t['akcio']['brutto'] ?? 'lejár',
                ];
            }
        }
    }

    private function hibaUzenet(array $report)
    {
        $t = [sprintf(
            'UNAS készlet/ár feltöltés: készlet %d hiba, ár %d hiba',
            $report['keszlet']['hiba'],
            $report['ar']['hiba']
        )];
        if ($report['hivashiba'] !== '') {
            $t[] = 'a menet leállt: ' . $report['hivashiba'];
        }
        foreach (array_slice($report['hibak'], 0, 5) as $h) {
            $t[] = $h['unasid'] . ': ' . $h['hiba'];
        }
        return implode("\n", $t);
    }

    private function emptyReport($szaraz, $keszletBe, $arBe)
    {
        return [
            'szaraz' => $szaraz,
            'keszletbe' => $keszletBe,
            'arbe' => $arBe,
            'celok' => 0,
            'duplikalt' => 0,
            'ismeretlen_unasid' => [],
            'kihagyva_hiba' => 0,
            'keszlet' => ['valtozott' => 0, 'kuldve' => 0, 'hiba' => 0, 'nemkeszletes' => 0, 'unasvaltozatos' => 0],
            'ar' => ['valtozott' => 0, 'kuldve' => 0, 'hiba' => 0, 'nincsar' => 0, 'akcios' => 0, 'akciolejarat' => 0],
            'hivasok' => 0,
            'valasz_tetel_nelkul' => 0,
            'fek' => false,
            'hivashiba' => '',
            'hibak' => [],
            'minta' => [],
        ];
    }
}
