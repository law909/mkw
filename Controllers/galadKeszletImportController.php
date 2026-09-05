<?php

namespace Controllers;

use Entities\Arfolyam;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Bizonylattipus;
use Entities\Partner;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekValtozat;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Az előző program készletének betöltése a "stock_detailed" formájú XLSX-ből:
 * raktáranként egy bevét a tulaj partnerre, "Induló készlet" megjegyzéssel.
 *
 * Oszlopok fejléc szerint: Cikkszám, Termék, Vonalkód, Raktár, Teljes mennyiség.
 * A raktárt a neve azonosítja, ha nincs ilyen, felveszi. A termék/termékváltozat
 * keresése előbb vonalkód, aztán cikkszám alapján megy, mindkettőn belül előbb a
 * változatot nézve. A kimaradó sorokról XLSX napló készül a storage/logs alá, azt a
 * naplo() végpont adja ki (a mappát a webszerver nem szolgálja ki).
 */
class galadKeszletImportController extends \mkwhelpers\Controller
{

    private const MEGJEGYZES = 'Induló készlet';
    private const NAPLOPREFIX = 'galadkeszlet_naplo_';
    private const NAPLOMINTA = '/^' . self::NAPLOPREFIX . '\d{8}_\d{6}\.xlsx$/';

    /** fejléc => a keresett oszlop lehetséges nevei (kisbetűsen) */
    private const OSZLOPOK = [
        'cikkszam' => ['cikkszám', 'cikkszam'],
        'nev' => ['termék', 'termek', 'megnevezés', 'megnevezes'],
        'vonalkod' => ['vonalkód', 'vonalkod'],
        'raktar' => ['raktár', 'raktar'],
        'mennyiseg' => ['teljes mennyiség', 'teljes mennyiseg', 'mennyiség', 'mennyiseg'],
    ];

    private $valtozatVonalkod = [];
    private $valtozatCikkszam = [];
    private $termekVonalkod = [];
    private $termekCikkszam = [];

    public function import()
    {
        @set_time_limit(600);
        @ini_set('memory_limit', '1024M');

        $filenev = \mkw\store::moveUploadedFile('toimport', 'galadkeszlet');
        if (!$filenev) {
            $this->json(['ok' => false, 'error' => 'Hiányzó vagy nem elfogadott típusú fájl.']);
            return;
        }
        $this->json($this->process($filenev));
    }

    /**
     * A feltöltött fájl feldolgozása. A visszaadott tömb megy a kliensnek JSON-ként.
     */
    private function process($filenev)
    {
        try {
            $reader = IOFactory::createReader(IOFactory::identify($filenev));
            $reader->setReadDataOnly(true);
            $excel = $reader->load($filenev);
            $rows = $excel->getActiveSheet()->toArray(null, true, false, false);
            $excel->disconnectWorksheets();
            unset($excel);
        } catch (\Exception $e) {
            return ['ok' => false, 'error' => 'A fájl nem olvasható táblázatként.'];
        }

        $oszlop = $this->columnMap(array_shift($rows) ?: []);
        foreach (['cikkszam', 'raktar', 'mennyiseg'] as $kotelezo) {
            if (!isset($oszlop[$kotelezo])) {
                return ['ok' => false, 'error' => 'Hiányzó oszlop a fejlécben: ' . self::OSZLOPOK[$kotelezo][0] . '.'];
            }
        }

        $kimaradt = [];
        $csoportok = $this->groupByRaktar($rows, $oszlop, $kimaradt);
        unset($rows);

        if (!$csoportok) {
            return [
                'ok' => true,
                'uzenet' => 'A fájlban nincs betölthető sor.',
                'bizonylatok' => [],
                'ujraktarak' => [],
                'kimaradt' => count($kimaradt),
                'naplo' => $this->writeNaplo($kimaradt),
            ];
        }

        $this->loadKodMaps();

        $em = $this->getEm();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            $bizonylatok = [];
            $ujraktarak = [];
            foreach ($csoportok as $raktarnev => $sorok) {
                // előbb a termékek: tétel nélküli bevétet nem viszünk fel
                $talalatok = [];
                $mennyiseg = 0;
                foreach ($sorok as $sor) {
                    $talalat = $this->findTermek($sor['vonalkod'], $sor['cikkszam']);
                    if (!$talalat) {
                        $sor['ok'] = 'Nem azonosítható termék (vonalkód/cikkszám).';
                        $kimaradt[] = $sor;
                        continue;
                    }
                    $talalat[] = $sor['mennyiseg'];
                    $talalatok[] = $talalat;
                    $mennyiseg += $sor['mennyiseg'];
                }
                if (!$talalatok) {
                    continue;
                }

                $raktar = $this->getOrCreateRaktar((string)$raktarnev, $ujraktarak);
                $fej = $this->createBevet($raktar);
                foreach ($talalatok as [$termek, $valtozat, $db]) {
                    $tetel = new Bizonylattetel();
                    $tetel->setBizonylatfej($fej);
                    $tetel->setPersistentData();
                    $tetel->setTermek($termek);
                    $tetel->setTermekvaltozat($valtozat);
                    $tetel->setMennyiseg($db);
                    $tetel->fillEgysar();
                    $tetel->calc();
                    $em->persist($tetel);
                }

                $em->flush();
                $bizonylatok[] = [
                    'raktar' => (string)$raktarnev,
                    'bizonylatszam' => $fej->getId(),
                    'tetel' => count($talalatok),
                    'mennyiseg' => $mennyiseg,
                ];
                $em->clear();
            }

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            return ['ok' => false, 'error' => 'Az import megszakadt: ' . $e->getMessage()];
        }

        return [
            'ok' => true,
            'uzenet' => count($bizonylatok) . ' bevét készült.',
            'bizonylatok' => $bizonylatok,
            'ujraktarak' => $ujraktarak,
            'kimaradt' => count($kimaradt),
            'naplo' => $this->writeNaplo($kimaradt),
        ];
    }

    /** A kimaradt sorok naplója. A storage/logs webről tiltva van, ezért session mögül adjuk. */
    public function naplo()
    {
        $file = basename(trim((string)$this->params->getStringRequestParam('fajl')));
        if (!preg_match(self::NAPLOMINTA, $file) || !is_readable(\mkw\store::logsPath($file))) {
            echo 'A napló nem található.';
            return;
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        readfile(\mkw\store::logsPath($file));
    }

    /**
     * Fejlécnevek => oszlopindexek. A fejlécben nem szereplő oszlop kimarad a tömbből.
     */
    private function columnMap(array $fejlec)
    {
        $nevek = [];
        foreach ($fejlec as $i => $cella) {
            $nev = mb_strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string)$cella)));
            if (($nev !== '') && !isset($nevek[$nev])) {
                $nevek[$nev] = $i;
            }
        }
        $oszlop = [];
        foreach (self::OSZLOPOK as $mezo => $lehetsegesek) {
            foreach ($lehetsegesek as $n) {
                if (isset($nevek[$n])) {
                    $oszlop[$mezo] = $nevek[$n];
                    break;
                }
            }
        }
        return $oszlop;
    }

    /**
     * A sorok raktárnév szerinti csoportjai. A be nem tölthető sorok a $kimaradt tömbbe
     * kerülnek az okukkal együtt.
     */
    private function groupByRaktar(array $rows, array $oszlop, array &$kimaradt)
    {
        $csoportok = [];
        foreach ($rows as $i => $row) {
            $sor = [
                // az adatsorok a fejléc alatt kezdődnek, ezért +2 az eredeti sorszám
                'sor' => $i + 2,
                'cikkszam' => $this->cellString($row[$oszlop['cikkszam']] ?? null),
                'nev' => isset($oszlop['nev']) ? $this->cellString($row[$oszlop['nev']] ?? null) : '',
                'vonalkod' => isset($oszlop['vonalkod']) ? $this->cellString($row[$oszlop['vonalkod']] ?? null) : '',
                'raktar' => $this->cellString($row[$oszlop['raktar']] ?? null),
                'mennyiseg' => $this->cellNumber($row[$oszlop['mennyiseg']] ?? null),
            ];
            if (($sor['cikkszam'] === '') && ($sor['vonalkod'] === '') && ($sor['raktar'] === '')) {
                continue;
            }
            if ($sor['raktar'] === '') {
                $sor['ok'] = 'Nincs raktár a soron.';
                $kimaradt[] = $sor;
                continue;
            }
            if (mb_strlen($sor['raktar']) > 50) {
                $sor['ok'] = 'A raktár neve 50 karakternél hosszabb.';
                $kimaradt[] = $sor;
                continue;
            }
            if ($sor['mennyiseg'] <= 0) {
                $sor['ok'] = 'Nem pozitív mennyiség.';
                $kimaradt[] = $sor;
                continue;
            }
            $csoportok[$sor['raktar']][] = $sor;
        }
        return $csoportok;
    }

    /**
     * A fájlban szereplő kódok kikeresése soronkénti findOneBy helyett: a vonalkód- és
     * cikkszám-térképeket egyszer töltjük be, és az em->clear() sem ejti el őket.
     */
    private function loadKodMaps()
    {
        $this->valtozatVonalkod = $this->loadKodMap(TermekValtozat::class, 'vonalkod');
        $this->valtozatCikkszam = $this->loadKodMap(TermekValtozat::class, 'cikkszam');
        $this->termekVonalkod = $this->loadKodMap(Termek::class, 'vonalkod');
        $this->termekCikkszam = $this->loadKodMap(Termek::class, 'cikkszam');
    }

    /** kód => id térkép; azonos kódnál a kisebb id nyer (a findOneBy-jal egyező találat) */
    private function loadKodMap($entity, $mezo)
    {
        $sorok = $this->getEm()
            ->createQuery('SELECT _xx.id, _xx.' . $mezo . ' AS kod FROM ' . $entity . ' _xx ORDER BY _xx.id ASC')
            ->getScalarResult();
        $map = [];
        foreach ($sorok as $sor) {
            $kod = trim((string)$sor['kod']);
            if (($kod !== '') && !isset($map[$kod])) {
                $map[$kod] = (int)$sor['id'];
            }
        }
        return $map;
    }

    /**
     * @return array{0: Termek, 1: TermekValtozat|null}|null
     */
    private function findTermek($vonalkod, $cikkszam)
    {
        $keresesek = [
            [$vonalkod, $this->valtozatVonalkod, $this->termekVonalkod],
            [$cikkszam, $this->valtozatCikkszam, $this->termekCikkszam],
        ];
        foreach ($keresesek as [$kod, $valtozatmap, $termekmap]) {
            if ($kod === '') {
                continue;
            }
            if (isset($valtozatmap[$kod])) {
                /** @var TermekValtozat|null $valtozat */
                $valtozat = $this->getEm()->find(TermekValtozat::class, $valtozatmap[$kod]);
                if ($valtozat && $valtozat->getTermek()) {
                    return [$valtozat->getTermek(), $valtozat];
                }
            }
            if (isset($termekmap[$kod])) {
                /** @var Termek|null $termek */
                $termek = $this->getEm()->find(Termek::class, $termekmap[$kod]);
                if ($termek) {
                    return [$termek, null];
                }
            }
        }
        return null;
    }

    private function getOrCreateRaktar($nev, array &$ujraktarak)
    {
        /** @var Raktar|null $raktar */
        $raktar = $this->getRepo(Raktar::class)->findOneBy(['nev' => $nev]);
        if ($raktar) {
            return $raktar;
        }
        $raktar = new Raktar();
        $raktar->setNev($nev);
        $raktar->setMozgat(true);
        $raktar->setArchiv(false);
        $raktar->setIdegenkod('');
        $this->getEm()->persist($raktar);
        // a bizonylatnak kész raktár kell, a mozgat() a raktáron múlik
        $this->getEm()->flush();
        $ujraktarak[] = $nev;
        return $raktar;
    }

    private function createBevet(Raktar $raktar)
    {
        /** @var Partner $partner */
        $partner = $this->getRepo(Partner::class)->find(\mkw\store::getParameter(\mkw\consts::Tulajpartner));

        $fej = new Bizonylatfej();
        $fej->setBizonylattipus($this->getRepo(Bizonylattipus::class)->find('bevet'));
        $fej->setPersistentData();
        $fej->setPartner($partner);
        $fej->setSzallitasimod($partner ? $partner->getSzallitasimod() : null);
        $fej->setKelt();
        $fej->setTeljesites();
        $fej->setEsedekesseg();
        if (!$fej->getValutanem()) {
            // a tulaj partneren nincs valutanem beállítva
            $fej->setValutanem($this->getRepo(Valutanem::class)->find(\mkw\store::getParameter(\mkw\consts::Valutanem)));
        }
        $arf = $this->getRepo(Arfolyam::class)->getActualArfolyam($fej->getValutanem(), $fej->getTeljesites());
        $fej->setArfolyam($arf->getArfolyam());
        $fej->setRaktar($raktar);
        $fej->setMegjegyzes(self::MEGJEGYZES);
        $this->getEm()->persist($fej);

        return $fej;
    }

    /** @return string|null a napló letöltő URL-je, ha készült napló */
    private function writeNaplo(array $kimaradt)
    {
        if (!$kimaradt) {
            return null;
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(['Sor', 'Cikkszám', 'Termék', 'Vonalkód', 'Raktár', 'Mennyiség', 'Kihagyás oka'], null, 'A1');
        $row = 2;
        foreach ($kimaradt as $sor) {
            $sheet->setCellValue('A' . $row, $sor['sor']);
            // a vezető nullás cikkszám/vonalkód szövegként marad meg
            $sheet->setCellValueExplicit('B' . $row, $sor['cikkszam'], DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $sor['nev']);
            $sheet->setCellValueExplicit('D' . $row, $sor['vonalkod'], DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $row, $sor['raktar']);
            $sheet->setCellValue('F' . $row, $sor['mennyiseg']);
            $sheet->setCellValue('G' . $row, $sor['ok']);
            $row++;
        }

        $file = self::NAPLOPREFIX . date('Ymd_His') . '.xlsx';
        (new Xlsx($spreadsheet))->save(\mkw\store::logsPath($file));
        $spreadsheet->disconnectWorksheets();

        return '/admin/import/galadkeszletnaplo?fajl=' . rawurlencode($file);
    }

    /** Cellaérték számként. Szövegként érkező mennyiségnél a tizedesvessző is jó. */
    private function cellNumber($val)
    {
        if (is_numeric($val)) {
            return (float)$val;
        }
        return (float)str_replace([' ', ','], ['', '.'], trim((string)$val));
    }

    /**
     * Cellaérték szövegként. A számként tárolt vonalkód float-ként érkezik, abból a
     * (string) cast tudományos alakot csinálna.
     */
    private function cellString($val)
    {
        if ($val === null) {
            return '';
        }
        if (is_float($val) && (floor($val) == $val) && (abs($val) < 1e15)) {
            return trim(sprintf('%.0F', $val));
        }
        return trim((string)$val);
    }

    private function json(array $data)
    {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
