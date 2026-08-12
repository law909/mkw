<?php

namespace Services;

use Entities\Raktar;
use Entities\Termek;
use Entities\TermekMinboltikeszlet;
use Entities\TermekValtozat;
use Entities\TermekValtozatMinboltikeszlet;
use mkwhelpers\FilterDescriptor;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * A minimum készletek tömeges karbantartásának két vége: Excel export és ugyanannak a
 * fájlnak a visszatöltése. A két irány itt, egy helyen van, mert az oszlopkiosztásukat
 * kötelező szinkronban tartani.
 *
 * Egy sor = egy termék VAGY egy változat. Változatos terméknél csak a változatokhoz adható meg
 * érték (lásd \Services\KeszletService feloldási létrája és a termékszerkesztő rácsa), ezért az
 * ilyen termék saját sora ki sem kerül az exportba – ha kézzel mégis betesznek egyet, az import
 * nullázza és figyelmeztet.
 *
 * Oszlopok: termék id, változat id, cikkszám, vonalkód, név, szín, méret, "Minden raktár",
 * majd raktáranként egy-egy oszlop. A raktároszlopokat az importáláskor a FEJLÉCBEN lévő
 * raktárnév azonosítja, nem a pozíció – így a fájl oszlopai átrendezhetők, de a raktárt
 * átnevezni két export között nem szabad.
 */
class MinKeszletExcelService
{

    /** az azonosító és leíró oszlopok a raktároszlopok előtt */
    private const FEJLECEK = ['Termék ID', 'Változat ID', 'Cikkszám', 'Vonalkód', 'Név', 'Szín', 'Méret', 'Minden raktár'];

    private const OSZLOP_TERMEKID = 0;
    private const OSZLOP_VALTOZATID = 1;
    private const OSZLOP_MINDENRAKTAR = 7;

    /** @var string[] raktárnevek id szerint – nem entitás, mert az export közben ürítjük az EM-et */
    private $raktarak = [];

    public function __construct()
    {
        foreach (\mkw\store::getEm()->getRepository(Raktar::class)->getAll(new FilterDescriptor(), ['nev' => 'ASC']) as $raktar) {
            $this->raktarak[$raktar->getId()] = $raktar->getNev();
        }
    }

    /**
     * @param int[] $termekids üresen minden termék
     */
    public function export(array $termekids = []): Spreadsheet
    {
        $excel = new Spreadsheet();
        $sheet = $excel->setActiveSheetIndex(0);

        $fejlecek = self::FEJLECEK;
        foreach ($this->raktarak as $raktarnev) {
            $fejlecek[] = $raktarnev;
        }
        foreach ($fejlecek as $i => $fejlec) {
            $sheet->setCellValue(\mkw\store::getExcelCoordinate($i) . '1', t($fejlec));
        }

        $sor = 2;
        foreach ($this->getTermekIdBlokkok($termekids) as $blokk) {
            $sor = $this->exportBlokk($sheet, $blokk, $sor);
        }
        return $excel;
    }

    /**
     * A fájl visszatöltése. Csak azokat a termékeket/változatokat módosítja, amelyek szerepelnek
     * benne; az üres vagy nulla raktárcella a raktáras felülírás törlését jelenti.
     *
     * @return array{sorok:int, termek:int, valtozat:int, hibak:string[]}
     */
    public function import($filepath): array
    {
        $reader = IOFactory::createReader(IOFactory::identify($filepath));
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($filepath)->getActiveSheet();

        $raktaroszlopok = $this->getRaktarOszlopok($sheet, $hibak);
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

            $mindenraktar = $this->cellaErtek($sheet, self::OSZLOP_MINDENRAKTAR, $row);
            $raktariertekek = [];
            foreach ($raktaroszlopok as $oszlop => $raktarid) {
                $raktariertekek[$raktarid] = $this->cellaErtek($sheet, $oszlop, $row);
            }

            if ($valtozatid) {
                /** @var TermekValtozat|null $valtozat */
                $valtozat = $em->getRepository(TermekValtozat::class)->find($valtozatid);
                if (!$valtozat) {
                    $hibak[] = sprintf(t('%d. sor: nincs %d azonosítójú változat'), $row, $valtozatid);
                    continue;
                }
                $valtozat->setMinboltikeszlet($mindenraktar);
                $em->persist($valtozat);
                $this->setRaktariErtekek(
                    TermekValtozatMinboltikeszlet::class,
                    'setTermekvaltozat',
                    $valtozat,
                    $em->getRepository(TermekValtozatMinboltikeszlet::class)->getRowsByTermekValtozatIds([$valtozatid])[$valtozatid] ?? [],
                    $raktariertekek
                );
                $valtozatdb++;
            } else {
                /** @var Termek|null $termek */
                $termek = $em->getRepository(Termek::class)->find($termekid);
                if (!$termek) {
                    $hibak[] = sprintf(t('%d. sor: nincs %d azonosítójú termék'), $row, $termekid);
                    continue;
                }
                if (\mkw\store::getSetupValue('termekvaltozat') && count($termek->getValtozatok() ?? [])) {
                    // változatos terméken a termékszint kötelezően nulla: a fájlban lévő értéket eldobjuk,
                    // és a korábbi raktáras sorokat is töröljük – ugyanaz a szabály, mint a rácson
                    if (($mindenraktar * 1) || array_filter($raktariertekek)) {
                        $hibak[] = sprintf(
                            t('%d. sor: a(z) %d azonosítójú terméknek van változata, a termékszintű minimum nem állítható – nullázva'),
                            $row,
                            $termekid
                        );
                    }
                    $termek->setMinboltikeszlet(0);
                    $em->persist($termek);
                    foreach ($em->getRepository(TermekMinboltikeszlet::class)->getRowsByTermek($termekid) as $sor) {
                        $em->remove($sor);
                    }
                } else {
                    $termek->setMinboltikeszlet($mindenraktar);
                    $em->persist($termek);
                    $this->setRaktariErtekek(
                        TermekMinboltikeszlet::class,
                        'setTermek',
                        $termek,
                        $em->getRepository(TermekMinboltikeszlet::class)->getRowsByTermek($termekid),
                        $raktariertekek
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
     * Az azonosító oszlopok utáni raktároszlopok fejléc (raktárnév) alapján.
     *
     * @param string[] $hibak kimenő: az ismeretlen fejlécű oszlopok
     *
     * @return array [oszlopindex => raktar_id]
     */
    private function getRaktarOszlopok($sheet, &$hibak): array
    {
        $hibak = [];
        $nevmap = [];
        foreach ($this->raktarak as $id => $raktarnev) {
            $nevmap[mb_strtolower(trim($raktarnev))] = $id;
        }

        $ret = [];
        $maxcol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
        for ($i = count(self::FEJLECEK); $i < $maxcol; $i++) {
            $fejlec = trim((string)$sheet->getCell(\mkw\store::getExcelCoordinate($i) . '1')->getValue());
            if ($fejlec === '') {
                continue;
            }
            $raktarid = $nevmap[mb_strtolower($fejlec)] ?? 0;
            if ($raktarid) {
                $ret[$i] = $raktarid;
            } else {
                $hibak[] = sprintf(t('A(z) "%s" fejlécű oszlop nem azonosítható raktárként, kimarad.'), $fejlec);
            }
        }
        return $ret;
    }

    /**
     * A raktáras felülírások beállítása: a nem nulla érték sort ír, a nulla/üres töröl –
     * ugyanaz a szabály, mint a termékszerkesztő rácsán.
     *
     * @param class-string $entitas
     * @param string $setter a hordozót beállító metódus neve
     * @param object $hordozo Termek vagy TermekValtozat
     * @param array $meglevo [raktar_id => sor]
     * @param array $ertekek [raktar_id => érték]
     */
    private function setRaktariErtekek($entitas, $setter, $hordozo, array $meglevo, array $ertekek): void
    {
        $em = \mkw\store::getEm();
        foreach ($ertekek as $raktarid => $ertek) {
            $sor = $meglevo[$raktarid] ?? null;
            if ($ertek * 1) {
                if (!$sor) {
                    $sor = new $entitas();
                    $sor->$setter($hordozo);
                    $sor->setRaktar($em->getRepository(Raktar::class)->find($raktarid));
                }
                $sor->setMinboltikeszlet($ertek);
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
        $termekraktari = $em->getRepository(TermekMinboltikeszlet::class)->getByTermekIds($termekids);
        $valtozatraktari = $valtozatids
            ? $em->getRepository(TermekValtozatMinboltikeszlet::class)->getByTermekValtozatIds($valtozatids)
            : [];

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
                    'mindenraktar' => $termek->getMinboltikeszlet(),
                    'raktari' => $termekraktari[$termek->getId()] ?? [],
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
                    'mindenraktar' => $valtozat->getMinboltikeszlet(),
                    'raktari' => $valtozatraktari[$valtozat->getId()] ?? [],
                ]);
            }
        }
        $em->clear();
        return $sor;
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
            (float)$adat['mindenraktar'],
        ];
        foreach ($this->raktarak as $raktarid => $raktarnev) {
            $ertekek[] = (float)($adat['raktari'][$raktarid] ?? 0);
        }
        foreach ($ertekek as $i => $ertek) {
            $sheet->setCellValue(\mkw\store::getExcelCoordinate($i) . $sor, $ertek);
        }
    }

}
