<?php

namespace Services;

use Entities\Termek;
use Entities\TermekValtozat;

/**
 * UNAS termék- és termékváltozat-azonosítás a `getProductDB` exportból.
 *
 * 50 000 termék nem fér egy kérésbe, ezért két lépés: {@see downloadProductDB()} letölt és
 * kötegindexet épít, a {@see processBatch()}-et pedig az admin képernyő hívja AJAX-ból, amíg
 * a fájl végére nem érünk.
 *
 * ÚJ TERMÉKET NEM HOZUNK LÉTRE – ami nincs meg, riportba kerül.
 * Lásd docs/unas-integracio.md.
 */
class UnasTermekImportService
{

    public const BATCH = 200;

    /** a képletöltés a szűk keresztmetszet */
    public const BATCHKEPCSV = 25;

    /** kötegenként EGY getProduct hívás, abból PREMIUM-on 30 van óránként */
    public const BATCHKEPGETPRODUCT = 100;

    private const LOCKTIMEOUT = 3600;

    private const FLUSHBATCH = 50;

    private const REPORTSAMPLE = 200;

    private const MAXCOMBINATIONS = 500;

    /** a diagnosztikai lekérdezés egy hívásban ennyi azonosítót enged */
    private const QUERYMAX = 50;

    /** ennyi karakter nyers XML megy vissza a képernyőre */
    private const QUERYRAWMAX = 400000;

    /** ennyi ideig újrahasználjuk a már letöltött termékadatbázist */
    private const DOWNLOADMAXAGE = 3600;

    /** ennyi tétlenség után egy félbehagyott sorablakos menet fájlja már nem védett */
    private const INPROGRESSMAXAGE = 86400;

    /**
     * Fejlécnév → logikai oszlop. Fix betű-index nem használható: az oszlopok helye a `Get*`
     * kapcsolóktól és a bolt beállításaitól függ. Az illesztés normalizált (lásd normalizeColumnName).
     */
    private const COLUMNS = [
        'cikkszam' => ['Cikkszám', 'SKU'],
        'unasid' => ['Azonosító a webáruházban', 'Termék azonosító', 'Azonosító'],
        'nev' => ['Termék Név', 'Név'],
        'statusz' => ['Státusz'],
        'rovidleiras' => ['Rövid Leírás'],
        'leiras' => ['Tulajdonságok', 'Hosszú Leírás'],
        'valtozat1' => ['Választható Tulajdonság 1'],
        'valtozat2' => ['Választható Tulajdonság 2'],
        'valtozat3' => ['Választható Tulajdonság 3'],
        'kepurl' => ['Kép link', 'Képek'],
        'kepalt' => ['Kép ALT/TITLE', 'Kép ALT'],
        'sefurl' => ['SEF URL'],
        'seotitle' => ['SEO Title'],
        'seodescription' => ['SEO Description'],
        'seokeywords' => ['SEO Keywords'],
        'modositas' => ['Utolsó módosítás'],
    ];

    /** ha legalább egy Get* be van kapcsolva, csak a jelölt oszlopok jönnek */
    private const GETFIELDS = [
        'GetId' => 1,
        'GetName' => 1,
        'GetStatus' => 1,
        'GetDescriptionShort' => 1,
        'GetDescriptionLong' => 1,
        'GetSeo' => 1,
        'GetVariant' => 1,
        'GetImage' => 1,
        'GetAddModDate' => 1,
        'GetURL' => 1,
        'GetType' => 1,
        'GetParam' => 1,
        'GetCategory' => 1,
    ];

    /** @var UnasService */
    private $unas;

    /** @var UnasKepService|null */
    private $kepService;

    public function __construct(?UnasService $unas = null)
    {
        $this->unas = $unas ?: new UnasService();
    }

    // ------------------------------------------------------------------
    // 1. lépés: letöltés
    // ------------------------------------------------------------------

    /**
     * @param array $opts lásd {@see defaultOptions()}
     *
     * @return array{fajl: string, osszes: int, koteg: int, fejlec: array, oszlopok: array,
     *               hianyzo: array, meret: int, kepforras: string, ujrahasznalt: bool,
     *               letoltve: string}
     * @throws \Exception ha már fut egy import, vagy nem jött használható fájl
     */
    public function downloadProductDB(array $opts)
    {
        $opts = $this->defaultOptions($opts);

        if ($this->isLocked()) {
            throw new \Exception(t('Már fut egy UNAS termékimport. Várja meg a végét, vagy oldja fel a zárolást.'));
        }
        $this->lock();

        try {
            $reused = $this->reusableDownload($opts);
            if ($reused) {
                $file = $reused['fajl'];
                // a fájl a letöltéskori állapotot tükrözi, ezért a kurzor is az az időpont
                $startTime = (int)$reused['ido'];
                $size = (int)(@filesize(\mkw\store::logsPath($file)) ?: 0);
            } else {
                [$file, $size, $startTime] = $this->fetchProductDB($opts);
            }

            $meta = $this->buildIndex($file, $opts, $startTime);
            $meta['meret'] = $size;
            $this->writeMeta($file, $meta);
            // sorablakos menet: a fájlt a takarítás nem viheti el a szakaszok között
            $this->markInProgress($file, $opts['sortol'] > 0);
            // Sorablakos menet folytatásakor a riport és a lista-CSV-k tovább gyűlnek. Ha a riport
            // nincs meg (mert nem is volt, vagy a „Riportok törlése" elvitte), nulláról kezdjük.
            if (!$this->isContinuation($opts) || !$this->reportExists($file)) {
                $this->initReport($file, $meta);
            }
            $this->cleanupOrphans();

            return [
                'fajl' => $file,
                'kezdosor' => $this->windowStart($meta),
                'osszes' => $this->windowEnd($meta),
                'sorok' => $meta['sorok'],
                'koteg' => $meta['koteg'],
                'fejlec' => $meta['fejlec'],
                'oszlopok' => array_keys($meta['oszlopok']),
                'hianyzo' => $meta['hianyzo'],
                'meret' => $size,
                'kepforras' => $meta['opts']['kepforras'],
                'ujrahasznalt' => (bool)$reused,
                'letoltve' => date(\mkw\store::$DateTimeFormat, $startTime),
            ];
        } catch (\Exception $e) {
            $this->unlock();
            throw $e;
        }
    }

    /**
     * getProductDB hívás + letöltés.
     *
     * @return array{0: string, 1: int, 2: int} fájlnév, méret, letöltés időpontja
     * @throws \Exception
     */
    private function fetchProductDB(array $opts)
    {
        $startTime = time();
        $api = $this->unas->getApi();

        $params = self::GETFIELDS + [
                'Format' => 'csv2',      // pontosvesszős, fgetcsv-vel közvetlenül olvasható
                'Compress' => 'no',      // v1: tömörítetlen (a ZipArchive-os ág későbbre marad)
                'Lang' => $opts['nyelv'],
            ];
        if ($opts['limitnum'] > 0) {
            $params['LimitNum'] = $opts['limitnum'];
            $params['LimitStart'] = 0;
        }
        if ($opts['inkrementalis']) {
            $cursor = (int)$this->readParameter(\mkw\consts::UnasTermekImportCursor);
            if ($cursor > 0) {
                $params['TimeStart'] = $cursor;
            }
        }

        $response = $api->getProductDB($params);
        if (!$response) {
            throw new \Exception(t('A termékadatbázis nem kérhető le.') . ' ' . $api->getLasterrorsAsString());
        }
        $url = isset($response->Url) ? trim((string)$response->Url) : '';
        if ($url === '') {
            throw new \Exception(t('Az UNAS nem adott vissza letöltési URL-t a termékadatbázishoz.'));
        }

        // a fájl 1 óráig él az UNAS-nál
        $downloaded = $api->downloadToFile($url, 'productdb', 'csv', $this->protectedFile());
        if (!$downloaded) {
            throw new \Exception(t('A termékadatbázis nem tölthető le.') . ' ' . $api->getLasterrorsAsString());
        }

        $this->writeParameter(\mkw\consts::UnasTermekImportLastDownload, json_encode([
            'fajl' => $downloaded['fajl'],
            'ido' => $startTime,
            'jelzes' => $this->downloadSignature($opts),
        ]));

        return [$downloaded['fajl'], (int)$downloaded['meret'], $startTime];
    }

    /**
     * Az egy óránál frissebb, azonos paraméterekkel letöltött fájl, ha van. A getProductDB a
     * legszűkösebb végpont (PREMIUM: 10 hívás/óra), ezért nem kérjük le újra fölöslegesen.
     *
     * @return array{fajl: string, ido: int}|null
     */
    private function reusableDownload(array $opts)
    {
        // Folytatáskor az újraletöltés önellentmondás: a tól-ig számok a MEGLÉVŐ fájlra
        // vonatkoznak, egy új exportban egészen más sorok lennének ott.
        if ($this->isContinuation($opts) && $opts['ujraletoltes']) {
            throw new \Exception(
                t(
                    'Folytatáshoz nem kérhető új fájl az UNAS-tól.'
                    . ' Vegye ki az újraletöltés pipát, vagy állítsa a sor tól-ig mezőket 0-0-ra.'
                )
            );
        }
        if ($opts['ujraletoltes']) {
            return null;
        }
        $data = json_decode((string)$this->readParameter(\mkw\consts::UnasTermekImportLastDownload), true);
        if (!is_array($data) || empty($data['fajl']) || empty($data['ido'])) {
            return null;
        }
        // Sorablakos menet FOLYTATÁSAKOR a fájlt semmi nem cserélheti le: a tól-ig számok arra a
        // fájlra vonatkoznak. Ezért ilyenkor sem az életkor, sem a paraméter-eltérés nem számít.
        if (!$this->isContinuation($opts)) {
            if (time() - (int)$data['ido'] >= self::DOWNLOADMAXAGE) {
                return null;
            }
            if (($data['jelzes'] ?? null) !== $this->downloadSignature($opts)) {
                return null;
            }
        }
        try {
            $data['fajl'] = $this->checkedFile($data['fajl']);
        } catch (\Exception $e) {
            if ($this->isContinuation($opts)) {
                throw new \Exception(
                    t(
                        'A folytatáshoz szükséges termékadatbázis már nem érhető el.'
                        . ' Állítsa a sor tól-ig mezőket 0-0-ra, és indítsa újra a menetet.'
                    )
                );
            }
            return null;
        }
        return $data;
    }

    /**
     * A folyamatban lévő sorablakos menet fájlja, amit a takarítás nem dobhat el.
     * Egy félbehagyott menet védettsége INPROGRESSMAXAGE után magától megszűnik.
     */
    private function protectedFile()
    {
        $data = json_decode((string)$this->readParameter(\mkw\consts::UnasTermekImportInProgress), true);
        if (!is_array($data) || empty($data['fajl']) || empty($data['ido'])) {
            return null;
        }
        return (time() - (int)$data['ido'] < self::INPROGRESSMAXAGE) ? $data['fajl'] : null;
    }

    /**
     * Külön paraméterben él, nem a letöltés-rekordban: azt minden újabb letöltés felülírja,
     * és ezzel a védelem is elveszne.
     */
    private function markInProgress($file, $inProgress)
    {
        if ($inProgress) {
            $this->writeParameter(
                \mkw\consts::UnasTermekImportInProgress,
                json_encode(['fajl' => $file, 'ido' => time()])
            );
            return;
        }
        // csak a SAJÁT menetünk jelölését törölhetjük
        if ($this->protectedFile() === $file) {
            $this->writeParameter(\mkw\consts::UnasTermekImportInProgress, '');
        }
    }

    /** A fájl TARTALMÁT meghatározó paraméterek – ezek eltérésekor újra kell tölteni. */
    private function downloadSignature(array $opts)
    {
        $parts = [$opts['nyelv'], (int)$opts['inkrementalis'], (int)$opts['limitnum']];
        if ($opts['inkrementalis']) {
            $parts[] = (int)$this->readParameter(\mkw\consts::UnasTermekImportCursor);
        }
        return implode('|', $parts);
    }

    /**
     * A legutóbbi letöltés a képernyőn megjelenítve.
     *
     * @return array{fajl: string, ido: string}|null
     */
    public function getLastDownload()
    {
        $data = json_decode((string)$this->readParameter(\mkw\consts::UnasTermekImportLastDownload), true);
        if (!is_array($data) || empty($data['fajl']) || empty($data['ido'])) {
            return null;
        }
        return [
            'fajl' => $data['fajl'],
            'ido' => date(\mkw\store::$DateTimeFormat, (int)$data['ido']),
        ];
    }

    /**
     * Fejléc, sorszám és a kötegek kezdő bájtpozíciója. Offsetek nélkül minden AJAX kör az
     * elejéről olvasna – 50 000 sornál az négyzetes.
     */
    private function buildIndex($file, array $opts, $startTime)
    {
        $abs = \mkw\store::logsPath($file);
        $fh = fopen($abs, 'r');
        if (!$fh) {
            throw new \Exception(t('A letöltött termékadatbázis nem olvasható.'));
        }

        $firstLine = fgets($fh);
        if ($firstLine === false) {
            fclose($fh);
            throw new \Exception(t('A letöltött termékadatbázis üres.'));
        }
        $convert = !mb_check_encoding($firstLine, 'UTF-8');
        $delimiter = $this->detectDelimiter($firstLine);

        rewind($fh);
        $headerRow = fgetcsv($fh, 0, $delimiter, '"', '');
        if (!$headerRow) {
            fclose($fh);
            throw new \Exception(t('A termékadatbázis fejléce nem olvasható.'));
        }
        $headerRow = $this->convertRowEncoding($headerRow, $convert);
        // UTF-8 BOM az első cella elejéről
        $headerRow[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$headerRow[0]);

        [$columns, $missing] = $this->mapHeader($headerRow);
        if (!isset($columns['cikkszam'])) {
            fclose($fh);
            throw new \Exception(
                t('A termékadatbázisban nincs "Cikkszám" oszlop, enélkül nem azonosítható a termék.')
                . ' ' . t('A kapott fejléc') . ': ' . implode(' | ', $headerRow)
            );
        }

        // a doksi szerint a "Kép link" oszlop nem mindig jön – a tényleges fejléc dönt
        if ($opts['kepforras'] === 'auto') {
            $opts['kepforras'] = isset($columns['kepurl']) ? 'csv' : 'getproduct';
        }

        $batchSizeValue = $this->batchSize($opts);
        $offsets = [ftell($fh)];
        $rows = 0;
        while (($row = fgetcsv($fh, 0, $delimiter, '"', '')) !== false) {
            if ($row === [null] || (count($row) === 1 && trim((string)$row[0]) === '')) {
                continue;   // üres sor
            }
            $rows++;
            if ($rows % $batchSizeValue === 0) {
                $offsets[] = ftell($fh);
            }
        }
        fclose($fh);

        return [
            'fajl' => $file,
            'kezdet' => $startTime,
            'delimiter' => $delimiter,
            'konvertal' => $convert,
            'fejlec' => $headerRow,
            'oszlopok' => $columns,
            'hianyzo' => $missing,
            'sorok' => $rows,
            'koteg' => $batchSizeValue,
            'offsetek' => $offsets,
            'opts' => $opts,
        ];
    }

    // ------------------------------------------------------------------
    // 2. lépés: kötegelt feldolgozás
    // ------------------------------------------------------------------

    /**
     * @param string $file a letöltött CSV NEVE (a storage/logs mappában)
     * @param int $from az első feldolgozandó sor (0-alapú, fejléc nélkül)
     *
     * @return array{tol: int, kovetkezo: int, osszes: int, feldolgozva: int, kesz: bool,
     *               megszakadt: bool, uzenet: string}
     * @throws \Exception érvénytelen fájlnévre vagy hiányzó menetre
     */
    public function processBatch($file, $from)
    {
        $file = $this->checkedFile($file);
        $meta = $this->readMeta($file);
        $opts = $meta['opts'];
        $from = max(0, (int)$from);

        // frissítjük, hogy hosszú futás közben ne évüljön el
        $this->lock();

        try {
            $rows = $this->readRows($file, $meta, $from);
            $report = $this->readReport($file);
        } catch (\Exception $e) {
            $this->unlock();
            throw $e;
        }

        $aborted = false;
        $message = '';
        if ($rows) {
            try {
                $aborted = $this->processRows($rows, $meta, $opts, $report, $file);
            } catch (\Exception $e) {
                $aborted = true;
                $message = $e->getMessage();
                \mkw\store::writelog('UNAS termékimport hiba: ' . $e->getMessage(), 'unas_api_error.txt');
                $report['hibak'][] = $e->getMessage();
            }
        }

        $report['feldolgozva'] = $from + count($rows);
        $report['megszakadt'] = $report['megszakadt'] || $aborted;
        $this->writeReport($file, $report);

        $end = $this->windowEnd($meta);
        $next = $from + count($rows);
        $done = $aborted || !$rows || $next >= $end;

        if ($done) {
            // a kurzor és az összesített hibasor csak akkor esedékes, ha a FÁJL is végigfutott
            $this->finish($file, $meta, $report, $end >= (int)$meta['sorok']);
        }

        return [
                'tol' => $from,
                'kovetkezo' => $next,
                'osszes' => $end,
                'sorok' => (int)$meta['sorok'],
                'feldolgozva' => count($rows),
                'kesz' => $done,
                'megszakadt' => $report['megszakadt'],
                'uzenet' => $message,
            ] + ($done ? $this->nextWindow($meta) : []);
    }

    /**
     * A tömeges kulcstérképek miatt kötegenként négy lekérdezésből megvan az azonosítás.
     *
     * @return bool megszakadt-e (az EntityManager becsukódott)
     */
    private function processRows(array $rows, array $meta, array $opts, array &$report, $file)
    {
        $em = \mkw\store::getEm();
        $columns = $meta['oszlopok'];

        $cikkszamok = [];
        $unasids = [];
        foreach ($rows as $row) {
            $cikkszam = $this->cell($row, $columns, 'cikkszam');
            if ($cikkszam !== '') {
                $cikkszamok[] = $cikkszam;
                // a cserélt alakot is le kell kérdezni, különben az `IN (…)` be sem hozná
                $csereltCikkszam = $this->swapUnderscore($cikkszam);
                if ($csereltCikkszam !== $cikkszam) {
                    $cikkszamok[] = $csereltCikkszam;
                }
            }
            $unasid = $this->cell($row, $columns, 'unasid');
            if ($unasid !== '') {
                $unasids[] = $unasid;
            }
        }

        // a cikkszám térképe kis/nagybetű-független kulcsú, az azonosítóé pontos
        $map = [
            'tUnasid' => $this->buildKeyMap(Termek::class, 'unasid', $unasids),
            'vUnasid' => $this->buildKeyMap(TermekValtozat::class, 'unasid', $unasids, true),
            'vCikkszam' => $this->buildKeyMap(TermekValtozat::class, 'cikkszam', $cikkszamok, true, true),
            'tCikkszam' => $this->buildKeyMap(Termek::class, 'cikkszam', $cikkszamok, false, true),
        ];

        // száraz futásban nem hívjuk: a getProduct órás kerete túl szűk próbafutásra
        $getproductKepek = [];
        if ($opts['kepek'] && !$opts['szarazfutas'] && $opts['kepforras'] === 'getproduct') {
            $getproductKepek = $this->getKepekWithGetProduct(
                $opts['unasidkihagy'] ? $this->unskippedCikkszamok($rows, $columns, $map) : $cikkszamok,
                $report
            );
        }

        $count = 0;
        foreach ($rows as $row) {
            try {
                $this->processRow($row, $meta, $opts, $map, $getproductKepek, $report);
            } catch (\Exception $e) {
                $this->addError($report, $e->getMessage());
                \mkw\store::writelog('UNAS termékimport sorhiba: ' . $e->getMessage(), 'unas_api_error.txt');
                if (!$em->isOpen()) {
                    return true;
                }
            }
            if (!$opts['szarazfutas'] && ++$count % self::FLUSHBATCH === 0) {
                if (!$this->flushClear($report)) {
                    return true;
                }
            }
        }

        if ($opts['szarazfutas']) {
            $em->clear();
            return false;
        }
        return !$this->flushClear($report);
    }

    /** A kihagyandó sorokra kár elkölteni a getProduct órás keretét. */
    private function unskippedCikkszamok(array $rows, array $columns, array $map)
    {
        $result = [];
        foreach ($rows as $row) {
            $cikkszam = $this->cell($row, $columns, 'cikkszam');
            if ($cikkszam === '') {
                continue;
            }
            $match = $this->matchTermek($cikkszam, $this->cell($row, $columns, 'unasid'), $map);
            if ($match['statusz'] === 'ok' && $match['mod'] === 'unasid') {
                continue;
            }
            $result[] = $cikkszam;
        }
        return $result;
    }

    /** Egy CSV sor: párosítás, mezőírás, változatok, képek. */
    private function processRow(array $row, array $meta, array $opts, array $map, array $getproductKepek, array &$report)
    {
        $columns = $meta['oszlopok'];
        $cikkszam = $this->cell($row, $columns, 'cikkszam');
        $unasid = $this->cell($row, $columns, 'unasid');
        $nev = $this->cell($row, $columns, 'nev');

        $report['osszes']++;

        if ($cikkszam === '' && $unasid === '') {
            return;
        }

        $match = $this->matchTermek($cikkszam, $unasid, $map);
        if ($match['statusz'] === 'ketertelmu') {
            $report['ketertelmu_db']++;
            $this->addSample($report, 'ketertelmu', [
                'cikkszam' => $cikkszam,
                'unasid' => $unasid,
                'nev' => $nev,
                'ok' => $match['ok'],
            ]);
            return;
        }
        if ($match['statusz'] === 'nincs') {
            $report['nem_talalt_db']++;
            $this->addSample($report, 'nem_talalt', ['cikkszam' => $cikkszam, 'unasid' => $unasid, 'nev' => $nev]);
            return;
        }

        /** @var Termek $termek */
        $termek = \mkw\store::getEm()->find(Termek::class, $match['termekid']);
        if (!$termek) {
            $report['nem_talalt_db']++;
            $this->addSample($report, 'nem_talalt', ['cikkszam' => $cikkszam, 'unasid' => $unasid, 'nev' => $nev]);
            return;
        }

        $report['parositott_' . $match['szint'] . '_' . $match['mod']]++;

        // Amit az UNAS azonosító alapján megtaláltunk, azt egy korábbi menet már párosította:
        // a kapcsolóval együtt onnantól hozzá sem nyúlunk (mező, változat, kép semmi).
        if ($opts['unasidkihagy'] && $match['mod'] === 'unasid') {
            $report['kihagyva_unasid']++;
            return;
        }

        // jelzés, hogy a törzs és az UNAS cikkszámformátuma eltér
        if (!empty($match['csere'])) {
            $report['cikkszam_csere_db']++;
            $this->addSample($report, 'cikkszam_csere', [
                'unas_cikkszam' => $cikkszam,
                'keresett' => $this->swapUnderscore($cikkszam),
                'nev' => $nev,
                'szint' => $match['szint'],
                'termekid' => $match['termekid'],
                'valtozatid' => $match['valtozatid'],
            ]);
        }

        // az azonosító oda kerül, AHOL a találat volt – így egy termék több UNAS terméknek is
        // megfelelhet, változatonként külön azonosítóval
        $unasidGazda = $termek;
        if ($match['szint'] === 'valtozat') {
            $valtozat = \mkw\store::getEm()->find(TermekValtozat::class, $match['valtozatid']);
            if ($valtozat) {
                $unasidGazda = $valtozat;
            }
        }

        if ($unasid !== '') {
            // ha más azonosító állt ott, a cikkszámos találat felülírja – de riportba is kerül
            $oldUnasid = trim((string)$unasidGazda->getUnasid());
            if ($oldUnasid !== '' && $oldUnasid !== $unasid) {
                $report['unasid_utkozes_db']++;
                $this->addSample($report, 'unasid_utkozes', [
                    'cikkszam' => $cikkszam,
                    'nev' => $nev,
                    'szint' => $match['szint'],
                    'termekid' => $termek->getId(),
                    'valtozatid' => $match['valtozatid'],
                    'regi' => $oldUnasid,
                    'uj' => $unasid,
                ]);
            }
            if (!$opts['szarazfutas']) {
                $unasidGazda->setUnasid($unasid);
            }
        }

        // a webes mezők mindig a TERMÉKRE mennek (a változatnak nincs leírása), így
        // változatszintű találatnál több UNAS termék írhatja ugyanazt
        if (!$opts['szarazfutas']) {
            $this->writeFields($termek, $row, $columns, $opts, $report);
        }

        $this->matchTermekValtozatok($termek, $row, $columns, $opts, $report, $cikkszam, $nev);

        if ($opts['kepek']) {
            $this->importKepek($termek, $row, $columns, $opts, $getproductKepek, $report);
        }
    }

    // ------------------------------------------------------------------
    // Párosítás
    // ------------------------------------------------------------------

    /**
     * Az azonosítási lánc (az azonosító oda kerül, ahol a találat volt):
     *
     * 1. `Termek.unasid`           = UNAS azonosító                 → termékre
     * 2. `TermekValtozat.unasid`   = UNAS azonosító                 → változatra
     * 3. `TermekValtozat.cikkszam` = UNAS cikkszám                  → változatra
     * 4. `Termek.cikkszam`         = UNAS cikkszám                  → termékre
     * 5. `TermekValtozat.cikkszam` = UNAS cikkszám, `_` helyett `-` → változatra
     * 6. `Termek.cikkszam`         = UNAS cikkszám, `_` helyett `-` → termékre
     *
     * Az 5–6. csak akkor fut, ha a cikkszám az eredeti alakjában sehol nem volt meg.
     * A cikkszám keresése kis/nagybetűre nem érzékeny; vonalkód nincs a láncban.
     * Kétértelmű találatnál (ugyanaz az érték több soron) megállunk, nem párosítunk.
     *
     * @return array{statusz: string, mod: string, szint: string, termekid: int|null,
     *               valtozatid: int|null, csere: bool, ok: string}
     */
    private function matchTermek($cikkszam, $unasid, array $map)
    {
        $foldedCikkszam = $this->foldKey($cikkszam);
        $steps = [
            ['mod' => 'unasid', 'terkep' => 'tUnasid', 'ertek' => $unasid, 'valtozat' => false, 'csere' => false],
            ['mod' => 'unasid', 'terkep' => 'vUnasid', 'ertek' => $unasid, 'valtozat' => true, 'csere' => false],
            ['mod' => 'cikkszam', 'terkep' => 'vCikkszam', 'ertek' => $foldedCikkszam, 'valtozat' => true, 'csere' => false],
            ['mod' => 'cikkszam', 'terkep' => 'tCikkszam', 'ertek' => $foldedCikkszam, 'valtozat' => false, 'csere' => false],
        ];

        // csak ha a csere tényleg változtat, különben ugyanazt keresnénk kétszer
        $csereltCikkszam = $this->foldKey($this->swapUnderscore($cikkszam));
        if ($csereltCikkszam !== '' && $csereltCikkszam !== $foldedCikkszam) {
            $steps[] = ['mod' => 'cikkszam', 'terkep' => 'vCikkszam', 'ertek' => $csereltCikkszam, 'valtozat' => true, 'csere' => true];
            $steps[] = ['mod' => 'cikkszam', 'terkep' => 'tCikkszam', 'ertek' => $csereltCikkszam, 'valtozat' => false, 'csere' => true];
        }

        foreach ($steps as $step) {
            if ($step['ertek'] === '' || !isset($map[$step['terkep']][$step['ertek']])) {
                continue;
            }
            $match = $map[$step['terkep']][$step['ertek']];
            $szint = $step['valtozat'] ? 'valtozat' : 'termek';
            if ($match === false) {
                return [
                    'statusz' => 'ketertelmu',
                    'mod' => $step['mod'],
                    'szint' => $szint,
                    'termekid' => null,
                    'valtozatid' => null,
                    'csere' => $step['csere'],
                    'ok' => ($step['valtozat'] ? 'változat' : 'termék') . ' ' . $step['mod']
                        . ($step['csere'] ? ' (_ → -)' : ''),
                ];
            }
            return [
                'statusz' => 'ok',
                'mod' => $step['mod'],
                'szint' => $szint,
                'termekid' => $step['valtozat'] ? $match['termekid'] : $match['id'],
                'valtozatid' => $step['valtozat'] ? $match['id'] : null,
                'csere' => $step['csere'],
                'ok' => '',
            ];
        }

        return [
            'statusz' => 'nincs',
            'mod' => '',
            'szint' => '',
            'termekid' => null,
            'valtozatid' => null,
            'csere' => false,
            'ok' => '',
        ];
    }

    /** Az UNAS és a törzs elválasztó karaktere gyakran eltér (`ABC_1` vs `ABC-1`). */
    private function swapUnderscore($cikkszam)
    {
        return str_replace('_', '-', (string)$cikkszam);
    }

    /**
     * A MySQL `_ci` kollációja miatt az `IN (…)` magától illeszt, de a térképben PHP tömbkulcs
     * a keresés – az pontos egyezést vár, ezért mindkét oldalt ugyanígy hajtjuk.
     */
    private function foldKey($ertek)
    {
        return mb_strtolower(trim((string)$ertek), 'UTF-8');
    }

    /**
     * Érték → azonosító térkép, 1000-esével darabolt DQL IN-nel. Entitást nem tartunk benne:
     * a kötegelt clear() leválasztaná. A többszörös találat `false` = kétértelmű.
     *
     * @param bool $fold kis/nagybetű-független kulcs (cikkszámhoz)
     *
     * @return array érték => ['id' => int, 'termekid' => int|null]  vagy  érték => false
     */
    private function buildKeyMap($entityClass, $field, array $values, $valtozat = false, $fold = false)
    {
        $map = [];
        $values = array_values(array_unique(array_filter($values, static function ($v) {
            return $v !== '' && $v !== null;
        })));
        if (!$values) {
            return $map;
        }
        foreach (array_chunk($values, 1000) as $chunk) {
            $qb = \mkw\store::getEm()->createQueryBuilder()
                ->select('e.' . $field . ' AS val', 'e.id AS id')
                ->from($entityClass, 'e')
                ->where('e.' . $field . ' IN (:vals)')
                ->setParameter('vals', $chunk);
            if ($valtozat) {
                $qb->addSelect('IDENTITY(e.termek) AS termekid');
            }
            foreach ($qb->getQuery()->getScalarResult() as $r) {
                $key = $fold ? $this->foldKey($r['val']) : trim((string)$r['val']);
                if ($key === '') {
                    continue;
                }
                $termekid = $valtozat ? (int)$r['termekid'] : null;
                if ($valtozat && !$termekid) {
                    continue;   // gazdátlan változat, nem használható
                }
                if (array_key_exists($key, $map)) {
                    // "ABC" és "abc" is ide fut be: az ugyanaz a cikkszám, nem tudunk választani
                    $map[$key] = false;
                    continue;
                }
                $map[$key] = ['id' => (int)$r['id'], 'termekid' => $termekid];
            }
        }
        return $map;
    }

    // ------------------------------------------------------------------
    // Változat-párosítás
    // ------------------------------------------------------------------

    /**
     * A `Választható Tulajdonság 1/2/3` Descartes-szorzatához keressük a mi változatainkat.
     * A keresés PHP-ban fut, nem `getByProperties()`-szel: az az értékeket nyersen a SQL-be
     * interpolálja, és UNAS-ból jövő adattal az injekció-kockázat.
     */
    private function matchTermekValtozatok(Termek $termek, array $row, array $columns, array $opts, array &$report, $cikkszam, $nev)
    {
        $properties = [];
        foreach (['valtozat1', 'valtozat2', 'valtozat3'] as $key) {
            $t = $this->parseVariantProperty($this->cell($row, $columns, $key));
            if ($t) {
                $properties[] = $t;
            }
        }
        if (!$properties) {
            return;
        }

        // az UNAS 3 tulajdonságot enged, a TermekValtozat csak 2-t kezel
        if (count($properties) > 2) {
            $report['harom_tulajdonsagu_db']++;
            $this->addSample($report, 'harom_tulajdonsagu', [
                'cikkszam' => $cikkszam,
                'nev' => $nev,
                'termekid' => $termek->getId(),
                'tulajdonsagok' => implode(', ', array_column($properties, 'nev')),
            ]);
            \mkw\store::writelog(
                'UNAS: 3 tulajdonságos termék, a változat-párosítás kimarad – cikkszám: ' . $cikkszam,
                'unas_api_error.txt'
            );
            return;
        }

        $valtozatok = $this->getTermekValtozatList($termek);
        if (!$valtozatok) {
            $report['valtozat_nincs_mkw_db']++;
            $this->addSample($report, 'valtozat_nincs_mkw', [
                'cikkszam' => $cikkszam,
                'nev' => $nev,
                'termekid' => $termek->getId(),
            ]);
            return;
        }

        $combinations = $this->cartesian($properties);
        if (count($combinations) > self::MAXCOMBINATIONS) {
            $this->addError(
                $report,
                sprintf(
                    t('A(z) %s cikkszámú terméknél %s változat-kombináció jött ki, ennyit nem párosítunk.'),
                    $cikkszam,
                    count($combinations)
                )
            );
            return;
        }

        $index = [];
        foreach ($valtozatok as $v) {
            $index[$this->buildSetKey($this->getValueSet($v))][] = $v;
        }

        $matched = [];
        foreach ($combinations as $combination) {
            $key = $this->buildSetKey(array_map([$this, 'normalizeValue'], $combination));
            $unasKey = implode('|', $combination);
            $candidates = $index[$key] ?? [];

            if (count($candidates) === 1) {
                $v = $candidates[0];
                if (!$opts['szarazfutas']) {
                    $v->setUnasvaltozat(mb_substr($unasKey, 0, 255));
                }
                $matched[spl_object_id($v)] = true;
                $report['valtozat_parositva']++;
                continue;
            }
            $report['valtozat_nem_talalt_db']++;
            $this->addSample($report, 'valtozat_nem_talalt', [
                'cikkszam' => $cikkszam,
                'nev' => $nev,
                'termekid' => $termek->getId(),
                'kombinacio' => $unasKey,
                'ok' => count($candidates) > 1 ? t('több egyező változat') : t('nincs egyező változat'),
            ]);
        }

        // amihez nem jött UNAS kombináció
        foreach ($valtozatok as $v) {
            if (isset($matched[spl_object_id($v)])) {
                continue;
            }
            $report['mkw_valtozat_parositatlan_db']++;
            $this->addSample($report, 'mkw_valtozat_parositatlan', [
                'cikkszam' => $cikkszam,
                'nev' => $nev,
                'termekid' => $termek->getId(),
                'valtozatid' => $v->getId(),
                'ertekek' => implode('|', $this->getValueSet($v)),
            ]);
        }
    }

    /**
     * `Szín:Piros|Sárga(+)1000|Zöld(+)-2000` → ['nev' => 'Szín', 'ertekek' => ['Piros','Sárga','Zöld']]
     * Az `(+)` utáni árkülönbözetet eldobjuk: az az UNAS ára, nem a miénk.
     */
    private function parseVariantProperty($value)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }
        $pos = mb_strpos($value, ':');
        if ($pos === false) {
            return null;
        }
        $nev = trim(mb_substr($value, 0, $pos));
        $values = [];
        foreach (explode('|', mb_substr($value, $pos + 1)) as $e) {
            $e = trim(preg_replace('/\(\+\)\s*-?[\d\s.,]*$/u', '', $e));
            if ($e !== '') {
                $values[] = $e;
            }
        }
        if (!$values) {
            return null;
        }
        return ['nev' => $nev, 'ertekek' => $values];
    }

    /** Descartes-szorzat az UNAS tulajdonság-sorrendjében. */
    private function cartesian(array $properties)
    {
        $result = [[]];
        foreach ($properties as $t) {
            $new = [];
            foreach ($result as $partial) {
                foreach ($t['ertekek'] as $e) {
                    $new[] = array_merge($partial, [$e]);
                }
            }
            $result = $new;
            if (count($result) > self::MAXCOMBINATIONS) {
                return $result;
            }
        }
        return $result;
    }

    /**
     * A `Termek::getValtozatok()` theme-függő: kollekció, tömb, NULL, vagy kivételt dob.
     *
     * @return TermekValtozat[]
     */
    private function getTermekValtozatList(Termek $termek)
    {
        try {
            $v = $termek->getValtozatok();
        } catch (\Exception $e) {
            return [];
        }
        if (!$v) {
            return [];
        }
        return is_array($v) ? $v : $v->toArray();
    }

    /**
     * Az `ertek1`/`ertek2` ÉS a `szin`/`meret` FK is kell: fix színmódban mindkettő ki van
     * töltve, és csak az egyiket nézve elmaradna a találat.
     *
     * @return string[] rendezett, ismétlés nélküli, normalizált értékek
     */
    private function getValueSet(TermekValtozat $v)
    {
        $values = [$v->getErtek1(), $v->getErtek2(), $v->getSzinNev(), $v->getMeretNev()];
        $set = [];
        foreach ($values as $e) {
            $n = $this->normalizeValue($e);
            if ($n !== '') {
                $set[$n] = true;
            }
        }
        return array_keys($set);
    }

    private function buildSetKey(array $values)
    {
        $values = array_values(array_unique(array_filter($values, static function ($e) {
            return $e !== '';
        })));
        sort($values);
        return implode('|', $values);
    }

    /** "Sötét Kék" és "sötétkék" ugyanaz. */
    private function normalizeValue($value)
    {
        return str_replace('-', '', \mkw\store::urlize(trim((string)$value)));
    }

    // ------------------------------------------------------------------
    // Mezőírás
    // ------------------------------------------------------------------

    /**
     * Kategória, láthatóság, státusz szándékosan NEM íródik. A `nev` sem: abból generálja a Gedmo
     * a slugot, tehát a termék publikus URL-je változna. Üres UNAS érték nem töröl.
     */
    private function writeFields(Termek $termek, array $row, array $columns, array $opts, array &$report)
    {
        $l1 = $opts['nyelvsuffix'] === '_l1';
        $written = false;

        if ($opts['editmezok']) {
            $written = $this->setIfNotEmpty($termek, $l1 ? 'setLeirasL1' : 'setLeiras', $this->cell($row, $columns, 'leiras')) || $written;
            $written = $this->setIfNotEmpty($termek, $l1 ? 'setRovidleirasL1' : 'setRovidleiras', $this->cell($row, $columns, 'rovidleiras')) || $written;
            $written = $this->setIfNotEmpty($termek, $l1 ? 'setOldalcimL1' : 'setOldalcim', $this->cell($row, $columns, 'seotitle'), 255) || $written;
            // a seodescription-nek és a seokeywords-nek nincs _l1 párja
            if (!$l1) {
                $written = $this->setIfNotEmpty($termek, 'setSeodescription', $this->cell($row, $columns, 'seodescription')) || $written;
                $written = $this->setIfNotEmpty($termek, 'setSeokeywords', $this->cell($row, $columns, 'seokeywords'), 255) || $written;
            }
            $written = $this->setIfNotEmpty($termek, 'setKepleiras', $this->cell($row, $columns, 'kepalt')) || $written;
        }

        if ($written) {
            $report['mezo_irva']++;
        }
    }

    /** Üres értékkel nem hívjuk a settert: a törzs értékét nem törölhetjük. */
    private function setIfNotEmpty(Termek $termek, $setter, $value, $maxLength = 0)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return false;
        }
        if ($maxLength > 0) {
            $value = mb_substr($value, 0, $maxLength);
        }
        $termek->$setter($value);
        return true;
    }

    // ------------------------------------------------------------------
    // Képek
    // ------------------------------------------------------------------

    private function importKepek(Termek $termek, array $row, array $columns, array $opts, array $getproductKepek, array &$report)
    {
        $alt = $this->cell($row, $columns, 'kepalt');

        if ($opts['kepforras'] === 'getproduct') {
            $cikkszam = $this->cell($row, $columns, 'cikkszam');
            $kepek = $getproductKepek[$cikkszam] ?? [];
        } else {
            $kepek = UnasKepService::getKepekFromColumn($this->cell($row, $columns, 'kepurl'), $alt);
        }
        if (!$kepek || $opts['szarazfutas']) {
            return;
        }

        // inkrementális menetben minden visszajött termék módosult
        $force = $opts['kepekujra'] || $opts['inkrementalis'];
        $result = $this->getKepService()->importKepek($termek, $kepek, $force);

        $report['kep_letoltve'] += $result['letoltve'];
        $report['kep_kihagyva'] += $result['kihagyva'];
        $report['kep_duplikatum'] += $result['duplikatum'];
        $report['kep_hozzarendelve'] += $result['hozzarendelve'];
        foreach ($result['hibak'] as $error) {
            $report['kep_hiba_db']++;
            $this->addSample($report, 'kep_hiba', ['uzenet' => $error]);
        }
    }

    /**
     * B-menet: egy `getProduct` hívás az egész köteg cikkszámaira, ha a CSV-ben nincs kép oszlop.
     *
     * @return array cikkszám => képlista
     */
    private function getKepekWithGetProduct(array $cikkszamok, array &$report)
    {
        $cikkszamok = array_values(array_unique(array_filter($cikkszamok)));
        if (!$cikkszamok) {
            return [];
        }
        $api = $this->unas->getApi();
        $response = $api->getProduct([
            'ContentType' => 'full',
            'Sku' => implode(',', $cikkszamok),
            'State' => 'live',
        ]);
        if (!$response) {
            $report['kep_hiba_db']++;
            $this->addSample($report, 'kep_hiba', ['uzenet' => t('getProduct hiba') . ': ' . $api->getLasterrorsAsString()]);
            return [];
        }

        $result = [];
        foreach ($response->Product ?? [] as $product) {
            $sku = isset($product->Sku) ? trim((string)$product->Sku) : '';
            if ($sku === '') {
                continue;
            }
            $result[$sku] = UnasKepService::getKepekFromProduct($product);
        }
        return $result;
    }

    private function getKepService()
    {
        if (!$this->kepService) {
            $this->kepService = new UnasKepService();
        }
        return $this->kepService;
    }

    // ------------------------------------------------------------------
    // Diagnosztikai lekérdezés
    // ------------------------------------------------------------------

    /**
     * Egy vagy több UNAS termék lekérdezése cikkszám vagy UNAS azonosító alapján. Semmit nem ír –
     * a nyers válasz és a belőle kiolvasott mezők a lényeg: a `getProductDB` üresen hagyja a
     * változat-oszlopokat, és csak innen derül ki, van-e egyáltalán változata a terméknek.
     *
     * @return array{keres: array, nyers: string, nyersteljes: bool, dumpfajl: string|null,
     *               termekek: array, tobbtermekes: bool, keret: array}
     */
    public function queryProduct(array $opts)
    {
        $sku = $this->splitIdList($opts['cikkszam'] ?? '');
        $id = $this->splitIdList($opts['unasid'] ?? '');
        if (!$sku && !$id) {
            throw new \Exception(t('Adjon meg cikkszámot vagy UNAS azonosítót.'));
        }
        // „Ha ezt a mezőt kitöltöd, akkor az Sku mezőt figyelmen kívül hagyjuk" – és listát sem fogad
        if ($id && count($id) > 1) {
            throw new \Exception(t('UNAS azonosítóból egyszerre csak egy kérdezhető le. Több termékhez a cikkszám mezőt használja.'));
        }
        if (count($sku) > self::QUERYMAX) {
            throw new \Exception(sprintf(t('Egyszerre legfeljebb %s cikkszám kérdezhető le.'), self::QUERYMAX));
        }

        $params = ['ContentType' => $this->checkedContentType($opts['contenttype'] ?? 'full')];
        if ($id) {
            $params['Id'] = $id[0];
        } else {
            $params['Sku'] = implode(',', $sku);
        }
        $params['State'] = ($opts['state'] ?? 'live') === 'deleted' ? 'deleted' : 'live';
        $lang = trim((string)($opts['lang'] ?? ''));
        if ($lang !== '') {
            $params['Lang'] = $lang;
        }

        $api = $this->unas->getApi();
        $response = $api->getProduct($params);
        $dump = $api->getLastDumpFile();
        if (!$response) {
            throw new \Exception(
                ($api->getLasterrorsAsString() ?: t('Az UNAS nem adott értelmezhető választ.'))
                . ($dump ? ' (' . $dump . ')' : '')
            );
        }

        $termekek = [];
        foreach ($response->Product ?? [] as $product) {
            $termekek[] = $this->summarizeProduct($product);
        }

        $raw = $this->readDump($dump) ?: (string)$response->asXML();
        $teljes = mb_strlen($raw) <= self::QUERYRAWMAX;

        return [
            'keres' => $params,
            'nyers' => $teljes ? $raw : mb_substr($raw, 0, self::QUERYRAWMAX),
            'nyersteljes' => $teljes,
            'dumpfajl' => $dump,
            'termekek' => $termekek,
            // a több termékes getProduct órás kerete jóval szűkebb (PREMIUM 30 vs. 1000)
            'tobbtermekes' => !$id && count($sku) > 1,
            'keret' => ['felhasznalt' => $api->rateCount('getProduct')],
        ];
    }

    /**
     * A képernyőn ez a lényeg: van-e `Variants` blokk, és ha igen, milyen értékekkel.
     *
     * @return array
     */
    private function summarizeProduct(\SimpleXMLElement $product)
    {
        $valtozatok = [];
        foreach ($product->Variants->Variant ?? [] as $variant) {
            $ertekek = [];
            foreach ($variant->Values->Value ?? [] as $value) {
                $ertekek[] = [
                    'nev' => trim((string)($value->Name ?? '')),
                    'arkulonbozet' => trim((string)($value->ExtraPrice ?? '')),
                ];
            }
            $valtozatok[] = ['nev' => trim((string)($variant->Name ?? '')), 'ertekek' => $ertekek];
        }

        $keszletek = [];
        foreach ($product->Stocks->Stock ?? [] as $stock) {
            $kombinacio = [];
            foreach ($stock->Variants->Variant ?? [] as $ertek) {
                $kombinacio[] = trim((string)$ertek);
            }
            $keszletek[] = [
                'kombinacio' => implode('|', $kombinacio),
                'mennyiseg' => trim((string)($stock->Qty ?? '')),
                'raktar' => trim((string)($stock->WarehouseId ?? '')),
            ];
        }

        $statusz = '';
        foreach ($product->Statuses->Status ?? [] as $status) {
            if (strtolower(trim((string)($status->Type ?? ''))) === 'base') {
                $statusz = trim((string)($status->Value ?? ''));
            }
        }

        return [
            'id' => trim((string)($product->Id ?? '')),
            'cikkszam' => trim((string)($product->Sku ?? '')),
            'nev' => trim((string)($product->Name ?? '')),
            'statusz' => $statusz,
            'me' => trim((string)($product->Unit ?? '')),
            'modositas' => $this->timestampToStr((string)($product->LastModTime ?? '')),
            'valtozatok' => $valtozatok,
            'kepek' => UnasKepService::getKepekFromProduct($product),
            'keszletek' => $keszletek,
        ];
    }

    /** Vesszővel, pontosvesszővel, szóközzel vagy sortöréssel is elválasztható. */
    private function splitIdList($value)
    {
        $parts = preg_split('/[\s,;]+/', trim((string)$value)) ?: [];
        return array_values(array_filter(array_map('trim', $parts), static fn($v) => $v !== ''));
    }

    private function checkedContentType($value)
    {
        $value = strtolower(trim((string)$value));
        return in_array($value, ['minimal', 'short', 'normal', 'full'], true) ? $value : 'full';
    }

    private function readDump($file)
    {
        if (!$file) {
            return '';
        }
        $abs = \mkw\store::logsPath(basename($file));
        return is_readable($abs) ? (string)file_get_contents($abs) : '';
    }

    private function timestampToStr($value)
    {
        $value = (int)trim((string)$value);
        return $value > 0 ? date(\mkw\store::$DateTimeFormat, $value) : '';
    }

    // ------------------------------------------------------------------
    // Doctrine kötegelés
    // ------------------------------------------------------------------

    /**
     * @return bool sikerült-e (hamis: az EntityManager becsukódott, tovább nem megy)
     */
    private function flushClear(array &$report)
    {
        $em = \mkw\store::getEm();
        try {
            $em->flush();
            $em->clear();
            return true;
        } catch (\Exception $e) {
            $this->addError($report, $e->getMessage());
            \mkw\store::writelog('UNAS termékimport flush hiba: ' . $e->getMessage(), 'unas_api_error.txt');
            // becsukódott EntityManagerrel már semmi nem menthető
            return $em->isOpen();
        }
    }

    // ------------------------------------------------------------------
    // Menet lezárása
    // ------------------------------------------------------------------

    /**
     * A következő sorablak a képernyőnek. A fájl végén 0/0, vagyis nincs több.
     *
     * @return array{kovetkezo_sortol: int, kovetkezo_sorig: int}
     */
    private function nextWindow(array $meta)
    {
        $rows = (int)$meta['sorok'];
        $end = $this->windowEnd($meta);
        $size = $end - $this->windowStart($meta);
        if ($size <= 0 || $end >= $rows) {
            return ['kovetkezo_sortol' => 0, 'kovetkezo_sorig' => 0];
        }
        return [
            'kovetkezo_sortol' => $end + 1,
            'kovetkezo_sorig' => min($end + $size, $rows),
        ];
    }

    /** Zárolás feloldása, kurzor léptetése (csak hibátlan futás után), egy összesített hibasor. */
    private function finish($file, array $meta, array &$report, $wholeFile = true)
    {
        if ($wholeFile) {
            $this->markInProgress($file, false);
        }
        $report['kurzormentve'] = false;
        $clean = !$report['megszakadt'] && !$report['hibak'] && !$report['nem_talalt_db'] && !$report['ketertelmu_db'];
        if ($wholeFile && $clean && empty($meta['opts']['szarazfutas']) && !empty($meta['kezdet'])) {
            $this->writeParameter(\mkw\consts::UnasTermekImportCursor, (int)$meta['kezdet']);
            $report['kurzormentve'] = true;
        }

        $messages = [];
        if ($report['nem_talalt_db']) {
            $messages[] = sprintf(t('%s UNAS termék nem található a törzsben, lásd a riportot.'), $report['nem_talalt_db']);
        }
        if ($report['ketertelmu_db']) {
            $messages[] = sprintf(t('%s UNAS termék azonosítása kétértelmű.'), $report['ketertelmu_db']);
        }
        if ($report['harom_tulajdonsagu_db']) {
            $messages[] = sprintf(t('%s terméknél 3 változat-tulajdonság van, ezeknél a változat-párosítás kimaradt.'), $report['harom_tulajdonsagu_db']);
        }
        if ($report['valtozat_nem_talalt_db']) {
            $messages[] = sprintf(t('%s UNAS változat-kombinációhoz nincs párosítható változat.'), $report['valtozat_nem_talalt_db']);
        }
        if ($report['kep_hiba_db']) {
            $messages[] = sprintf(t('%s képhiba.'), $report['kep_hiba_db']);
        }
        if ($report['megszakadt']) {
            $messages[] = t('A feldolgozás hiba miatt megszakadt.');
        }
        if ($messages) {
            $this->unas->logApiError(
                t('UNAS termékimport') . ' (' . $file . '): ' . implode(' ', $messages),
                $file,
                'unastermek'
            );
        }

        $this->writeReport($file, $report);
        $this->unlock();
    }

    // ------------------------------------------------------------------
    // CSV olvasás
    // ------------------------------------------------------------------

    /** A kötegindexből fseek-elünk, nem a fájl elejéről olvasunk. */
    private function readRows($file, array $meta, $from)
    {
        $limit = min((int)$meta['koteg'], $this->windowEnd($meta) - $from);
        if ($limit <= 0) {
            return [];
        }
        $batchSizeValue = (int)$meta['koteg'];
        $batchIndex = (int)floor($from / $batchSizeValue);
        $offsets = $meta['offsetek'];
        if (!isset($offsets[$batchIndex])) {
            return [];
        }

        $fh = fopen(\mkw\store::logsPath($file), 'r');
        if (!$fh) {
            throw new \Exception(t('A letöltött termékadatbázis nem olvasható.'));
        }
        fseek($fh, (int)$offsets[$batchIndex]);

        // eltolás, ha a hívó nem pont kötegkezdetről kért
        $toSkip = $from - $batchIndex * $batchSizeValue;
        $rows = [];
        while (count($rows) < $limit) {
            $row = fgetcsv($fh, 0, $meta['delimiter'], '"', '');
            if ($row === false) {
                break;
            }
            if ($row === [null] || (count($row) === 1 && trim((string)$row[0]) === '')) {
                continue;
            }
            if ($toSkip > 0) {
                $toSkip--;
                continue;
            }
            $rows[] = $this->convertRowEncoding($row, !empty($meta['konvertal']));
        }
        fclose($fh);
        return $rows;
    }

    private function convertRowEncoding(array $row, $convert)
    {
        if (!$convert) {
            return $row;
        }
        // ha mégsem UTF-8, a magyar Windows-alap az ISO-8859-2
        foreach ($row as $i => $cellValue) {
            $row[$i] = mb_convert_encoding((string)$cellValue, 'UTF-8', 'ISO-8859-2');
        }
        return $row;
    }

    private function detectDelimiter($row)
    {
        $candidates = [';' => substr_count($row, ';'), ',' => substr_count($row, ','), "\t" => substr_count($row, "\t")];
        arsort($candidates);
        $first = array_key_first($candidates);
        return $candidates[$first] > 0 ? $first : ';';
    }

    /**
     * Fejlécnév → oszlopindex.
     *
     * @return array{0: array<string,int>, 1: array<string>} [megtalált oszlopok, hiányzó logikai nevek]
     */
    private function mapHeader(array $header)
    {
        $normHeader = [];
        foreach ($header as $i => $nev) {
            $n = $this->normalizeColumnName($nev);
            if ($n !== '' && !isset($normHeader[$n])) {
                $normHeader[$n] = $i;
            }
        }

        $columns = [];
        $missing = [];
        foreach (self::COLUMNS as $logical => $valtozatok) {
            foreach ($valtozatok as $valtozat) {
                $n = $this->normalizeColumnName($valtozat);
                if (isset($normHeader[$n])) {
                    $columns[$logical] = $normHeader[$n];
                    continue 2;
                }
            }
            $missing[] = $valtozatok[0];
        }
        return [$columns, $missing];
    }

    private function normalizeColumnName($nev)
    {
        return str_replace('-', '', \mkw\store::urlize(trim((string)$nev)));
    }

    private function cell(array $row, array $columns, $logical)
    {
        if (!isset($columns[$logical]) || !isset($row[$columns[$logical]])) {
            return '';
        }
        return trim((string)$row[$columns[$logical]]);
    }

    // ------------------------------------------------------------------
    // Opciók
    // ------------------------------------------------------------------

    private function defaultOptions(array $opts)
    {
        $suffix = ($opts['nyelvsuffix'] ?? '') === '_l1' ? '_l1' : '';
        $inkrementalis = !empty($opts['inkrementalis']);
        return [
            'nyelvsuffix' => $suffix,
            'nyelv' => $suffix === '_l1' ? UnasService::getLangL1() : UnasService::getLang(),
            'szarazfutas' => !empty($opts['szarazfutas']),
            'inkrementalis' => $inkrementalis,
            // Alapból bekapcsolva: a hiányzó kulcs nem "ki", hanem "alapértelmezés". Inkrementális
            // menetben viszont épp a már párosított tételek jönnek vissza, ott kihagyni őket annyi
            // lenne, mint el sem indítani az importot.
            'unasidkihagy' => !$inkrementalis
                && (!array_key_exists('unasidkihagy', $opts) || !empty($opts['unasidkihagy'])),
            'sortol' => max(0, (int)($opts['sortol'] ?? 0)),
            'sorig' => max(0, (int)($opts['sorig'] ?? 0)),
            'editmezok' => !empty($opts['editmezok']),
            'ujraletoltes' => !empty($opts['ujraletoltes']),
            'kepek' => !empty($opts['kepek']),
            'kepekujra' => !empty($opts['kepekujra']),
            'kepforras' => in_array($opts['kepforras'] ?? 'auto', ['auto', 'csv', 'getproduct'], true)
                ? ($opts['kepforras'] ?? 'auto') : 'auto',
            'limitnum' => max(0, (int)($opts['limitnum'] ?? 0)),
        ];
    }

    /**
     * A feldolgozandó sorablak 0-alapú, kizárólagos vége. Ablak nélkül a fájl vége.
     * A `sortol`/`sorig` 1-alapú és zárt intervallum (a felhasználó így adja meg).
     */
    private function windowEnd(array $meta)
    {
        $rows = (int)$meta['sorok'];
        $to = (int)($meta['opts']['sorig'] ?? 0);
        return ($to > 0 && $to < $rows) ? $to : $rows;
    }

    /** 0-alapú kezdősor az ablakból. */
    private function windowStart(array $meta)
    {
        return max(0, (int)($meta['opts']['sortol'] ?? 0) - 1);
    }

    /** Folytatás-e egy már futó, sorablakos menet (ilyenkor a riport nem indul újra). */
    private function isContinuation(array $opts)
    {
        return (int)($opts['sortol'] ?? 0) > 1;
    }

    /** A kötegméret a legszűkebb erőforráshoz igazodik – lásd a BATCH* konstansokat. */
    private function batchSize(array $opts)
    {
        if (!$opts['kepek']) {
            return self::BATCH;
        }
        return $opts['kepforras'] === 'getproduct' ? self::BATCHKEPGETPRODUCT : self::BATCHKEPCSV;
    }

    // ------------------------------------------------------------------
    // Menet-fájlok (meta, riport, nem talált lista)
    // ------------------------------------------------------------------

    /**
     * A basename() és a szigorú minta együtt zárja ki a storage/logs mappán kívülre mutatást.
     *
     * @throws \Exception
     */
    public function checkedFile($file)
    {
        $file = basename(trim((string)$file));
        if (!preg_match('/^unas_productdb_\d{8}_\d{6}_\d{3}\.csv$/', $file)) {
            throw new \Exception(t('Érvénytelen import fájl.'));
        }
        if (!is_readable(\mkw\store::logsPath($file))) {
            throw new \Exception(t('Az import fájl már nem található, indítsa újra a letöltést.'));
        }
        return $file;
    }

    private function sideFile($file, $suffix, $ext)
    {
        return substr($file, 0, -4) . '_' . $suffix . '.' . $ext;
    }

    /**
     * A kliens csak az utolsó néhány termékadatbázist tartja meg, az ott maradt mellékfájlok
     * (meta, riport, listák) így gazdátlanná válnak.
     */
    private function cleanupOrphans()
    {
        foreach (glob(\mkw\store::logsPath('unas_productdb_*_*_???_*.*')) ?: [] as $abs) {
            if (preg_match('/^(unas_productdb_\d{8}_\d{6}_\d{3})_.+\.(csv|json)$/', basename($abs), $m)
                && !is_file(\mkw\store::logsPath($m[1] . '.csv'))) {
                @unlink($abs);
            }
        }
    }

    /**
     * A riportok és a lista-CSV-k eldobása. A letöltött termékadatbázis és a `_meta.json` marad:
     * abból folytatható a félbehagyott sorablakos menet.
     *
     * @param bool $apply false esetén csak megszámolja, mit vinne el
     *
     * @return int ahány fájlt töröltünk (száraz futásban: törölnénk)
     */
    public function deleteReports($apply = false)
    {
        $count = 0;
        foreach (glob(\mkw\store::logsPath('unas_productdb_*_*_???_*.*')) ?: [] as $abs) {
            if (!preg_match('/^unas_productdb_\d{8}_\d{6}_\d{3}_(.+)\.(csv|json)$/', basename($abs), $m)) {
                continue;
            }
            if ($m[1] === 'meta') {
                continue;
            }
            if (!$apply || @unlink($abs)) {
                $count++;
            }
        }
        return $count;
    }

    private function writeMeta($file, array $meta)
    {
        file_put_contents(
            \mkw\store::logsPath($this->sideFile($file, 'meta', 'json')),
            json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * @throws \Exception
     */
    private function readMeta($file)
    {
        $abs = \mkw\store::logsPath($this->sideFile($file, 'meta', 'json'));
        $meta = is_readable($abs) ? json_decode(file_get_contents($abs), true) : null;
        if (!is_array($meta) || !isset($meta['offsetek'], $meta['oszlopok'], $meta['opts'])) {
            throw new \Exception(t('Az import menet adatai nem találhatók, indítsa újra a letöltést.'));
        }
        return $meta;
    }

    private function initReport($file, array $meta)
    {
        $report = [
            'fajl' => $file,
            'kezdet' => date(\mkw\store::$DateTimeFormat),
            'sorok' => $meta['sorok'],
            'feldolgozva' => 0,
            'osszes' => 0,
            // a lánc négy lépcsője külön-külön: termek/valtozat × unasid/cikkszam
            'parositott_termek_unasid' => 0,
            'parositott_valtozat_unasid' => 0,
            'parositott_valtozat_cikkszam' => 0,
            'parositott_termek_cikkszam' => 0,
            // ebből hányat csak az aláhúzás→kötőjel cserével találtunk meg
            'cikkszam_csere_db' => 0,
            // az azonosító alapján megtalált, ezért érintetlenül hagyott tételek
            'kihagyva_unasid' => 0,
            'valtozat_parositva' => 0,
            'mezo_irva' => 0,
            'kep_letoltve' => 0,
            'kep_kihagyva' => 0,
            // ahány kép ténylegesen a termékhez került (főkép + TermekKep sor)
            'kep_hozzarendelve' => 0,
            // ugyanaz a fotó más néven: az UNAS a változat cikkszáma szerint nevezi a képeket
            'kep_duplikatum' => 0,
            'nem_talalt_db' => 0,
            'ketertelmu_db' => 0,
            'unasid_utkozes_db' => 0,
            'harom_tulajdonsagu_db' => 0,
            'valtozat_nem_talalt_db' => 0,
            'valtozat_nincs_mkw_db' => 0,
            'mkw_valtozat_parositatlan_db' => 0,
            'kep_hiba_db' => 0,
            'nem_talalt' => [],
            'ketertelmu' => [],
            'unasid_utkozes' => [],
            'harom_tulajdonsagu' => [],
            'valtozat_nem_talalt' => [],
            'valtozat_nincs_mkw' => [],
            'mkw_valtozat_parositatlan' => [],
            'cikkszam_csere' => [],
            'kep_hiba' => [],
            'hibak' => [],
            'megszakadt' => false,
            'kurzormentve' => false,
            'szarazfutas' => !empty($meta['opts']['szarazfutas']),
            'hianyzo_oszlopok' => $meta['hianyzo'],
        ];
        // A lista-CSV-k hozzáfűzéssel épülnek. Újrahasznált fájlnál a mellékfájlok neve is
        // ugyanaz, ezért indulás előtt eldobjuk őket – különben az előző menet sorai bennragadnának.
        foreach (glob(\mkw\store::logsPath(substr($file, 0, -4) . '_*.csv')) ?: [] as $abs) {
            @unlink($abs);
        }

        $this->writeReport($file, $report);
        return $report;
    }

    private function writeReport($file, array $report)
    {
        file_put_contents(
            \mkw\store::logsPath($this->sideFile($file, 'riport', 'json')),
            json_encode($report, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * A menet riportja.
     *
     * @throws \Exception ha nincs riport a menethez
     */
    public function getReport($file)
    {
        $file = $this->checkedFile($file);
        return $this->readReport($file);
    }

    private function reportExists($file)
    {
        return is_readable(\mkw\store::logsPath($this->sideFile($file, 'riport', 'json')));
    }

    private function readReport($file)
    {
        $abs = \mkw\store::logsPath($this->sideFile($file, 'riport', 'json'));
        $report = is_readable($abs) ? json_decode(file_get_contents($abs), true) : null;
        if (!is_array($report)) {
            throw new \Exception(t('Az import riportja nem található, indítsa újra a letöltést.'));
        }
        return $report;
    }

    /**
     * A "nem talált" lista CSV-jének abszolút útvonala (a felhasználó következő munkája).
     */
    public function getNotFoundCsvPath($file)
    {
        $file = $this->checkedFile($file);
        $abs = \mkw\store::logsPath($this->sideFile($file, 'nem_talalt', 'csv'));
        return is_readable($abs) ? $abs : null;
    }

    /**
     * A riport egy tétele. A képernyőre csak az első pár száz kerül (tízezer sor úgysem nézhető
     * át), a TELJES lista listánként külön CSV-be megy a letöltött adatbázis mellé.
     */
    private function addSample(array &$report, $list, array $item)
    {
        if (count($report[$list]) < self::REPORTSAMPLE) {
            $report[$list][] = $item;
        }
        $this->appendListRow($report['fajl'], $list, $item);
    }

    /** A riportlisták szöveges hibái ugyanúgy listába és CSV-be mennek. */
    private function addError(array &$report, $message)
    {
        $report['hibak'][] = $message;
        $this->appendListRow($report['fajl'], 'hibak', ['uzenet' => $message]);
    }

    /**
     * Egy sor a lista CSV-jébe. A fejlécet az első sor kulcsaiból írjuk ki, BOM-mal, hogy az
     * Excel is jól nyissa meg.
     */
    private function appendListRow($file, $list, array $item)
    {
        $abs = \mkw\store::logsPath($this->sideFile($file, $list, 'csv'));
        $new = !is_file($abs);
        $fh = @fopen($abs, 'a');
        if (!$fh) {
            return;
        }
        if ($new) {
            fwrite($fh, "\xEF\xBB\xBF");
            fputcsv($fh, array_keys($item), ';', '"', '');
        }
        fputcsv($fh, array_map(static fn($v) => is_scalar($v) ? $v : json_encode($v), $item), ';', '"', '');
        fclose($fh);
    }

    // ------------------------------------------------------------------
    // Zárolás – nyers DBAL-lal, hogy egy becsukódott EntityManager mellett is működjön
    // ------------------------------------------------------------------

    public function isLocked()
    {
        $value = (int)$this->readParameter(\mkw\consts::RunningUnasTermekImport);
        return $value > 0 && (time() - $value) < self::LOCKTIMEOUT;
    }

    private function lock()
    {
        $this->writeParameter(\mkw\consts::RunningUnasTermekImport, time());
    }

    public function unlock()
    {
        $this->writeParameter(\mkw\consts::RunningUnasTermekImport, 0);
    }

    private function writeParameter($key, $value)
    {
        \mkw\store::getEm()->getConnection()->executeStatement(
            'INSERT INTO parameterek (id, ertek, specialchars) VALUES (?, ?, 0)'
            . ' ON DUPLICATE KEY UPDATE ertek = VALUES(ertek), specialchars = 0',
            [$key, (string)$value]
        );
    }

    private function readParameter($key)
    {
        return \mkw\store::getEm()->getConnection()->fetchOne(
            'SELECT ertek FROM parameterek WHERE id = ?',
            [$key]
        );
    }

}
