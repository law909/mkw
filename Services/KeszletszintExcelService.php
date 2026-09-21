<?php

namespace Services;

use Entities\Raktar;
use Entities\Termek;
use Entities\TermekMinkeszlet;
use Entities\TermekOptkeszlet;
use Entities\TermekValtozat;
use Entities\TermekValtozatMinkeszlet;
use Entities\TermekValtozatOptkeszlet;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * A minimum és optimum készletek tömeges karbantartásának két vége: Excel export és ugyanannak a
 * fájlnak a visszatöltése. A két irány itt, egy helyen van, mert az oszlopkiosztásukat
 * kötelező szinkronban tartani.
 *
 * Egy sor = egy termék VAGY egy változat. Változatos terméknél csak a változatokhoz adható meg
 * érték (lásd \Services\KeszletService feloldási létrája és a termékszerkesztő rácsa), ezért az
 * ilyen termék saját sora ki sem kerül az exportba – ha kézzel mégis betesznek egyet, az import
 * nullázza és figyelmeztet.
 *
 * Oszlopok: termék id, változat id, cikkszám, vonalkód, név, szín, méret, majd értékoszlopok
 * párban: előbb a "Minden raktár" minimum és optimum, utána raktáranként a minimum és az optimum.
 * Az értékoszlop fejléce `<id>_<min|opt>_<név>` (pl. `3_min_KISKER RAKTÁR`), a globálisé
 * `<min|opt>_<név>`: importáláskor ez a két jelölő azonosítja az oszlopot, nem a pozíció és nem a
 * név – így a fájl oszlopai átrendezhetők, és a raktár két export között át is nevezhető.
 *
 * A 2026.09. előtti, csak minimumot tartalmazó fájlok is betölthetők: ott a `<id>_<név>` és a
 * csak nevet tartalmazó fejléc is a minimum oszlopot jelenti, az archivált raktár nevével együtt.
 * Amelyik szint nincs a fájlban, azt az import nem bántja.
 */
class KeszletszintExcelService
{

    /** az azonosító és leíró oszlopok az értékoszlopok előtt */
    private const FEJLECEK = ['Termék ID', 'Változat ID', 'Cikkszám', 'Vonalkód', 'Név', 'Szín', 'Méret'];

    private const OSZLOP_TERMEKID = 0;
    private const OSZLOP_VALTOZATID = 1;

    /** a raktárankénti oszlopok előtt álló, minden raktárra érvényes oszlop fejléce */
    private const MINDENRAKTAR = 'Minden raktár';

    /**
     * A két készletszint ugyanaz a rács, csak más entitáson és más metódusokon – a kettő között
     * csak ez a tömb tesz különbséget (a termékszerkesztő KESZLETMATRIXOK leírójának a párja).
     */
    private const SZINTEK = [
        'min' => [
            'termekentity' => TermekMinkeszlet::class,
            'valtozatentity' => TermekValtozatMinkeszlet::class,
            'getter' => 'getMinkeszlet',
            'setter' => 'setMinkeszlet',
            'cimke' => 'minimum készlet',
        ],
        'opt' => [
            'termekentity' => TermekOptkeszlet::class,
            'valtozatentity' => TermekValtozatOptkeszlet::class,
            'getter' => 'getOptkeszlet',
            'setter' => 'setOptkeszlet',
            'cimke' => 'optimum készlet',
        ],
    ];

    /** @var string[] az export oszlopai: a nem archivált raktárak neve id szerint – nem entitás, mert az export közben ürítjük az EM-et */
    private $raktarak = [];

    /** @var string[] minden raktár neve id szerint: az import a korábbi fájlok archivált oszlopát is felismeri */
    private $mindenraktar = [];

    public function __construct()
    {
        foreach (\mkw\store::getEm()->getRepository(Raktar::class)->getAll(new FilterDescriptor(), ['nev' => 'ASC']) as $raktar) {
            $this->mindenraktar[$raktar->getId()] = $raktar->getNev();
            if (!$raktar->getArchiv()) {
                $this->raktarak[$raktar->getId()] = $raktar->getNev();
            }
        }
    }

    /**
     * @param int[] $termekids üresen minden termék
     */
    public function export(array $termekids = []): Spreadsheet
    {
        $excel = new Spreadsheet();
        $sheet = $excel->setActiveSheetIndex(0);

        $fejlecek = array_map('t', self::FEJLECEK);
        foreach (array_keys(self::SZINTEK) as $szint) {
            $fejlecek[] = self::ertekFejlec(0, t(self::MINDENRAKTAR), $szint);
        }
        foreach ($this->raktarak as $raktarid => $raktarnev) {
            foreach (array_keys(self::SZINTEK) as $szint) {
                $fejlecek[] = self::ertekFejlec($raktarid, $raktarnev, $szint);
            }
        }
        foreach ($fejlecek as $i => $fejlec) {
            $sheet->setCellValue(\mkw\store::getExcelCoordinate($i) . '1', $fejlec);
        }

        $sor = 2;
        foreach ($this->getTermekIdBlokkok($termekids) as $blokk) {
            $sor = $this->exportBlokk($sheet, $blokk, $sor);
        }
        return $excel;
    }

    /**
     * A fájl visszatöltése. Csak azokat a termékeket/változatokat módosítja, amelyek szerepelnek
     * benne, és csak azokat a szinteket, amelyekhez van oszlop; az üres vagy nulla raktárcella a
     * raktáras felülírás törlését jelenti.
     *
     * @return array{sorok:int, termek:int, valtozat:int, hibak:string[]}
     */
    public function import($filepath): array
    {
        $reader = IOFactory::createReader(IOFactory::identify($filepath));
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($filepath)->getActiveSheet();

        $oszlopok = $this->getErtekOszlopok($sheet, $hibak);
        $em = \mkw\store::getEm();
        $maxrow = (int)$sheet->getHighestRow();
        $termekdb = 0;
        $valtozatdb = 0;
        $sorok = 0;

        for ($row = 2; $row <= $maxrow; ++$row) {
            $termekid = (int)$sheet->getCell(\mkw\store::getExcelCoordinate(self::OSZLOP_TERMEKID) . $row)->getValue();
            $valtozatid = (int)$sheet->getCell(\mkw\store::getExcelCoordinate(self::OSZLOP_VALTOZATID) . $row)->getValue();
            if (!$termekid && !$valtozatid) {
                continue;
            }
            $sorok++;

            $ertekek = [];
            foreach ($oszlopok as $szint => $szintoszlopok) {
                $ertekek[$szint] = [
                    'globalis' => is_null($szintoszlopok['globalis'])
                        ? null
                        : $this->cellaErtek($sheet, $szintoszlopok['globalis'], $row),
                    'raktari' => [],
                ];
                foreach ($szintoszlopok['raktari'] as $oszlop => $raktarid) {
                    $ertekek[$szint]['raktari'][$raktarid] = $this->cellaErtek($sheet, $oszlop, $row);
                }
            }

            if ($valtozatid) {
                /** @var TermekValtozat|null $valtozat */
                $valtozat = $em->getRepository(TermekValtozat::class)->find($valtozatid);
                if (!$valtozat) {
                    $hibak[] = sprintf(t('%d. sor: nincs %d azonosítójú változat'), $row, $valtozatid);
                    continue;
                }
                foreach ($ertekek as $szint => $ertek) {
                    $leiro = self::SZINTEK[$szint];
                    if (!is_null($ertek['globalis'])) {
                        $valtozat->{$leiro['setter']}($ertek['globalis']);
                        $em->persist($valtozat);
                    }
                    $this->setRaktariErtekek(
                        $leiro['valtozatentity'],
                        'setTermekvaltozat',
                        $leiro['setter'],
                        $valtozat,
                        $em->getRepository($leiro['valtozatentity'])->getRowsByTermekValtozatIds([$valtozatid])[$valtozatid] ?? [],
                        $ertek['raktari']
                    );
                }
                $valtozatdb++;
            } else {
                /** @var Termek|null $termek */
                $termek = $em->getRepository(Termek::class)->find($termekid);
                if (!$termek) {
                    $hibak[] = sprintf(t('%d. sor: nincs %d azonosítójú termék'), $row, $termekid);
                    continue;
                }
                $vanvaltozat = \mkw\store::getSetupValue('termekvaltozat') && count($termek->getValtozatok() ?? []);
                $zarolt = [];
                foreach ($ertekek as $szint => $ertek) {
                    $leiro = self::SZINTEK[$szint];
                    if ($vanvaltozat) {
                        // változatos terméken a termékszint kötelezően nulla: a fájlban lévő értéket eldobjuk,
                        // és a korábbi raktáras sorokat is töröljük – ugyanaz a szabály, mint a rácson
                        if (($ertek['globalis'] * 1) || array_filter($ertek['raktari'])) {
                            $zarolt[] = t($leiro['cimke']);
                        }
                        $termek->{$leiro['setter']}(0);
                        $em->persist($termek);
                        foreach ($em->getRepository($leiro['termekentity'])->getRowsByTermek($termekid) as $sor) {
                            $em->remove($sor);
                        }
                        continue;
                    }
                    if (!is_null($ertek['globalis'])) {
                        $termek->{$leiro['setter']}($ertek['globalis']);
                        $em->persist($termek);
                    }
                    $this->setRaktariErtekek(
                        $leiro['termekentity'],
                        'setTermek',
                        $leiro['setter'],
                        $termek,
                        $em->getRepository($leiro['termekentity'])->getRowsByTermek($termekid),
                        $ertek['raktari']
                    );
                }
                if ($zarolt) {
                    $hibak[] = sprintf(
                        t('%d. sor: a(z) %d azonosítójú terméknek van változata, a termékszintű érték nem állítható (%s) – nullázva'),
                        $row,
                        $termekid,
                        implode(', ', $zarolt)
                    );
                }
                $termekdb++;
            }

            if (($sorok % 200) === 0) {
                $em->flush();
            }
        }
        $em->flush();

        return ['sorok' => $sorok, 'termek' => $termekdb, 'valtozat' => $valtozatdb, 'hibak' => $hibak];
    }

    /**
     * Egy értékoszlop fejléce. Az id és a min/opt jelölő azonosít, a név csak az embernek szól;
     * a globális oszlopnak nincs raktár id-ja.
     */
    private static function ertekFejlec($raktarid, $raktarnev, $szint): string
    {
        return ($raktarid ? $raktarid . '_' : '') . $szint . '_' . $raktarnev;
    }

    /**
     * Az azonosító oszlopok utáni értékoszlopok a fejlécük alapján, szintenként csoportosítva.
     * A 2026.09. előtti fájlok fejlécét (`<id>_<név>`, csak név, `Minden raktár`) minimumként
     * fogadjuk el.
     *
     * @param string[] $hibak kimenő: az ismeretlen fejlécű oszlopok
     *
     * @return array [ szint => ['globalis' => oszlopindex|null, 'raktari' => [oszlopindex => raktar_id]] ]
     */
    private function getErtekOszlopok($sheet, &$hibak): array
    {
        $hibak = [];
        $nevmap = [];
        foreach ($this->mindenraktar as $id => $raktarnev) {
            $nevmap[mb_strtolower(trim($raktarnev))] = $id;
        }
        $globalisnevek = [mb_strtolower(t(self::MINDENRAKTAR)), mb_strtolower(self::MINDENRAKTAR)];

        $ret = [];
        $maxcol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($i = count(self::FEJLECEK); $i < $maxcol; $i++) {
            $fejlec = trim((string)$sheet->getCell(\mkw\store::getExcelCoordinate($i) . '1')->getValue());
            if ($fejlec === '') {
                continue;
            }

            $szint = 'min';
            $raktarid = null;
            if (preg_match('/^(\d+)_(min|opt)_/', $fejlec, $m)) {
                $szint = $m[2];
                $raktarid = isset($this->mindenraktar[(int)$m[1]]) ? (int)$m[1] : null;
            } elseif (preg_match('/^(min|opt)_/', $fejlec, $m)) {
                $szint = $m[1];
                $raktarid = 0;
            } elseif (preg_match('/^(\d+)_/', $fejlec, $m)) {
                $raktarid = isset($this->mindenraktar[(int)$m[1]]) ? (int)$m[1] : null;
            } elseif (in_array(mb_strtolower($fejlec), $globalisnevek, true)) {
                $raktarid = 0;
            } else {
                $raktarid = $nevmap[mb_strtolower($fejlec)] ?? null;
            }

            if (is_null($raktarid)) {
                $hibak[] = sprintf(t('A(z) "%s" fejlécű oszlop nem azonosítható, kimarad.'), $fejlec);
                continue;
            }
            $ret[$szint] ??= ['globalis' => null, 'raktari' => []];
            if ($raktarid) {
                $ret[$szint]['raktari'][$i] = $raktarid;
            } else {
                $ret[$szint]['globalis'] = $i;
            }
        }
        return $ret;
    }

    /**
     * A raktáras felülírások beállítása: a nem nulla érték sort ír, a nulla/üres töröl –
     * ugyanaz a szabály, mint a termékszerkesztő rácsán.
     *
     * @param class-string $entitas
     * @param string $hordozoSetter a hordozót beállító metódus neve
     * @param string $ertekSetter az értéket beállító metódus neve (SZINTEK)
     * @param object $hordozo Termek vagy TermekValtozat
     * @param array $meglevo [raktar_id => sor]
     * @param array $ertekek [raktar_id => érték]
     */
    private function setRaktariErtekek($entitas, $hordozoSetter, $ertekSetter, $hordozo, array $meglevo, array $ertekek): void
    {
        $em = \mkw\store::getEm();
        foreach ($ertekek as $raktarid => $ertek) {
            $sor = $meglevo[$raktarid] ?? null;
            if ($ertek * 1) {
                if (!$sor) {
                    $sor = new $entitas();
                    $sor->$hordozoSetter($hordozo);
                    $sor->setRaktar($em->getRepository(Raktar::class)->find($raktarid));
                }
                $sor->$ertekSetter($ertek);
                $em->persist($sor);
            } elseif ($sor) {
                $em->remove($sor);
            }
        }
    }

    private function cellaErtek($sheet, $oszlop, $row)
    {
        $ertek = trim((string)$sheet->getCell(\mkw\store::getExcelCoordinate($oszlop) . $row)->getValue());
        return $ertek === '' ? 0 : (float)str_replace(',', '.', $ertek);
    }

    /**
     * A termék id-k kezelhető méretű blokkokban: a teljes törzs exportja különben az egész
     * termékfát (változatostul) memóriában tartaná.
     *
     * @param int[] $termekids
     *
     * @return \Generator<int[]>
     */
    private function getTermekIdBlokkok(array $termekids)
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $termekids))));
        if (!$ids) {
            $ids = \mkw\store::getEm()->getConnection()
                ->executeQuery('SELECT id FROM termek ORDER BY cikkszam, nev')
                ->fetchFirstColumn();
        }
        foreach (array_chunk($ids, 300) as $blokk) {
            yield $blokk;
        }
    }

    /**
     * @param int[] $termekids
     *
     * @return int a következő szabad sor
     */
    private function exportBlokk($sheet, array $termekids, $sor)
    {
        $em = \mkw\store::getEm();
        $filter = new FilterDescriptor();
        $filter->addFilter('id', 'IN', $termekids);
        $termekek = $em->getRepository(Termek::class)->getWithValtozatok($filter);

        $valtozatosmod = \mkw\store::getSetupValue('termekvaltozat');
        $valtozatids = [];
        foreach ($termekek as $termek) {
            foreach ($termek->getValtozatok() ?? [] as $valtozat) {
                $valtozatids[] = $valtozat->getId();
            }
        }
        $termekraktari = [];
        $valtozatraktari = [];
        foreach (self::SZINTEK as $szint => $leiro) {
            $termekraktari[$szint] = $em->getRepository($leiro['termekentity'])->getByTermekIds($termekids);
            $valtozatraktari[$szint] = $valtozatids
                ? $em->getRepository($leiro['valtozatentity'])->getByTermekValtozatIds($valtozatids)
                : [];
        }

        /** @var Termek $termek */
        foreach ($termekek as $termek) {
            // változatos terméken a termékszint kötelezően nulla, a sorának nincs értelme –
            // a feltétel ugyanaz, mint az importban és a termékszerkesztő rácsán
            if (!$valtozatosmod || !count($termek->getValtozatok() ?? [])) {
                $this->exportSor($sheet, $sor++, [
                    'termekid' => $termek->getId(),
                    'valtozatid' => '',
                    'cikkszam' => $termek->getCikkszam(),
                    'vonalkod' => $termek->getVonalkod(),
                    'nev' => $termek->getNev(),
                    'szin' => '',
                    'meret' => '',
                    'hordozo' => $termek,
                    'raktari' => $this->szintenkentiErtekek($termekraktari, $termek->getId()),
                ]);
            }
            /** @var TermekValtozat $valtozat */
            foreach ($termek->getValtozatok() ?? [] as $valtozat) {
                $this->exportSor($sheet, $sor++, [
                    'termekid' => $termek->getId(),
                    'valtozatid' => $valtozat->getId(),
                    'cikkszam' => $valtozat->getCikkszam() ?: $termek->getCikkszam(),
                    'vonalkod' => $valtozat->getVonalkod() ?: $termek->getVonalkod(),
                    'nev' => $termek->getNev(),
                    'szin' => $valtozat->getErtek1(),
                    'meret' => $valtozat->getErtek2(),
                    'hordozo' => $valtozat,
                    'raktari' => $this->szintenkentiErtekek($valtozatraktari, $valtozat->getId()),
                ]);
            }
        }
        $em->clear();
        return $sor;
    }

    /**
     * @param array $szintmap [ szint => [ hordozo_id => [raktar_id => érték] ] ]
     *
     * @return array [ szint => [raktar_id => érték] ]
     */
    private function szintenkentiErtekek(array $szintmap, $id): array
    {
        $ret = [];
        foreach ($szintmap as $szint => $ertekek) {
            $ret[$szint] = $ertekek[$id] ?? [];
        }
        return $ret;
    }

    private function exportSor($sheet, $sor, array $adat): void
    {
        $ertekek = [
            $adat['termekid'],
            $adat['valtozatid'],
            $adat['cikkszam'],
            $adat['vonalkod'],
            $adat['nev'],
            $adat['szin'],
            $adat['meret'],
        ];
        foreach (self::SZINTEK as $szint => $leiro) {
            $ertekek[] = (float)$adat['hordozo']->{$leiro['getter']}();
        }
        foreach ($this->raktarak as $raktarid => $raktarnev) {
            foreach (self::SZINTEK as $szint => $leiro) {
                $ertekek[] = (float)($adat['raktari'][$szint][$raktarid] ?? 0);
            }
        }
        foreach ($ertekek as $i => $ertek) {
            $sheet->setCellValue(\mkw\store::getExcelCoordinate($i) . $sor, $ertek);
        }
    }

}
