<?php

namespace Services;

use Doctrine\DBAL\ArrayParameterType;
use Entities\TermekFa;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * A nem egyedi termékváltozat-cikkszámok kimutatása: az ütköző cikkszámok változatonként az okukkal, a több terméken
 * használt termékcikkszámok, a terméken többször felvett szín+méret, és az FC-MOTO készletexporton belüli ütközések.
 * Csak azokkal a termékekkel foglalkozik, amelyek kategóriáján (termekfa1, örökléssel) be van kapcsolva a szín+méretes
 * cikkszám – máshol a változat cikkszáma nem ebből a szabályból jön. A cikkszámokat a MySQL rendezése szerint
 * hasonlítja, tehát a kis- és nagybetű nem számít.
 */
class TermekValtozatCikkszamReportService
{
    private const REASON_TERMEKCIKKSZAM = 'a termékcikkszám több terméken is szerepel';
    private const REASON_SZINMERET = 'ugyanaz a szín+méret többször a terméken';
    private const REASON_KOD = 'eltérő szín/méret azonos kóddal';

    /** @var int[] a szín+méretes cikkszámú termékfa ágak */
    private array $termekfaIds = [];

    /**
     * @param array<int, true> $fcmotoValtozatIds az FC-MOTO készletexport változatai
     */
    public function createSpreadsheet(array $fcmotoValtozatIds): Spreadsheet
    {
        // üres lista mellett az IN () hibás lenne, a 0 id-jű ág pedig nem létezik
        $this->termekfaIds = \mkw\store::getEm()->getRepository(TermekFa::class)->getSzinmeretcikkszamIds() ?: [0];
        $excel = new Spreadsheet();
        [$collisions, $fcmotoCollisions] = $this->getCollisions($fcmotoValtozatIds);
        $this->writeSheet(
            $excel->getActiveSheet(),
            'Ütköző cikkszámok',
            ['Cikkszám', 'Hány változaton', 'Ok', 'Termék ID', 'Termék cikkszám', 'Termék neve', 'Termék inaktív', 'Változat ID', 'Szín', 'Méret',
                'Vonalkód', 'Változat látható', 'Változat inaktív', 'FC-MOTO exportban'],
            $collisions
        );
        $this->writeSheet(
            $excel->createSheet(),
            'Több terméken',
            ['Termék cikkszám', 'Hány terméken', 'Termék ID', 'Termék neve', 'Inaktív', 'Látható', 'Változatok száma'],
            $this->getSharedTermekCikkszamok()
        );
        $this->writeSheet(
            $excel->createSheet(),
            'Duplikált szín+méret',
            ['Termék ID', 'Termék cikkszám', 'Termék neve', 'Szín', 'Méret', 'Hányszor', 'Változat ID', 'Vonalkód', 'Változat látható', 'Változat inaktív',
                'FC-MOTO exportban'],
            $this->getDuplicateSzinMeret($fcmotoValtozatIds)
        );
        $this->writeSheet(
            $excel->createSheet(),
            'FC-MOTO ütközések',
            ['Cikkszám', 'Hány változaton', 'Termék ID', 'Termék neve', 'Változat ID', 'Szín', 'Méret', 'Vonalkód (EAN)'],
            $fcmotoCollisions
        );
        $excel->setActiveSheetIndex(0);
        return $excel;
    }

    private function getCollisions(array $fcmotoValtozatIds): array
    {
        $conn = \mkw\store::getEm()->getConnection();
        $szinTipus = \mkw\store::getParameter(\mkw\consts::ValtozatTipusSzin);
        $meretTipus = \mkw\store::getParameter(\mkw\consts::ValtozatTipusMeret);
        $rows = $conn->fetchAllAssociative(
            'SELECT x.cikkszam AS csoport, x.db, tv.id, tv.cikkszam, tv.vonalkod, tv.lathato, tv.inaktiv, tv.termek_id, t.cikkszam AS termekcikkszam,'
            . ' t.nev AS termeknev, t.inaktiv AS termekinaktiv, tv.adattipus1_id, tv.ertek1, tv.adattipus2_id, tv.ertek2, s.nev AS szinnev, m.nev AS meretnev'
            . ' FROM termekvaltozat tv'
            . ' INNER JOIN (SELECT tv2.cikkszam, COUNT(*) AS db FROM termekvaltozat tv2'
            . ' INNER JOIN termek t2 ON t2.id = tv2.termek_id AND t2.termekfa1_id IN (?)'
            . ' WHERE tv2.cikkszam <> "" GROUP BY tv2.cikkszam HAVING COUNT(*) > 1) x'
            . ' ON x.cikkszam = tv.cikkszam'
            . ' INNER JOIN termek t ON t.id = tv.termek_id AND t.termekfa1_id IN (?)'
            . ' LEFT JOIN szin s ON s.id = tv.szin_id'
            . ' LEFT JOIN meret m ON m.id = tv.meret_id'
            . ' ORDER BY x.cikkszam, tv.termek_id, tv.id',
            [$this->termekfaIds, $this->termekfaIds],
            [ArrayParameterType::INTEGER, ArrayParameterType::INTEGER]
        );
        // színtörzs nélküli (régi, szöveges) változatnál a TermekValtozat::getSzin()/getMeret() szövege
        $ertek = fn(array $row, $tipus) => match (true) {
            $tipus && $row['adattipus1_id'] == $tipus => (string)$row['ertek1'],
            $tipus && $row['adattipus2_id'] == $tipus => (string)$row['ertek2'],
            default => '',
        };
        $groups = [];
        foreach ($rows as $row) {
            $row['szin'] = $row['szinnev'] ?? $ertek($row, $szinTipus);
            $row['meret'] = $row['meretnev'] ?? $ertek($row, $meretTipus);
            $groups[$row['csoport']][] = $row;
        }

        $collisions = [];
        $fcmotoCollisions = [];
        foreach ($groups as $group) {
            $reason = $this->getReason($group);
            $fcmotoGroup = [];
            foreach ($group as $row) {
                $inFcmoto = isset($fcmotoValtozatIds[$row['id']]);
                $collisions[] = [
                    $row['cikkszam'], (int)$row['db'], $reason, (int)$row['termek_id'], $row['termekcikkszam'], $row['termeknev'],
                    $this->yes($row['termekinaktiv']), (int)$row['id'], $row['szin'], $row['meret'], (string)$row['vonalkod'],
                    $this->yes($row['lathato']), $this->yes($row['inaktiv']), $this->yes($inFcmoto),
                ];
                if ($inFcmoto) {
                    $fcmotoGroup[] = $row;
                }
            }
            if (count($fcmotoGroup) > 1) {
                foreach ($fcmotoGroup as $row) {
                    $fcmotoCollisions[] = [
                        $row['cikkszam'], count($fcmotoGroup), (int)$row['termek_id'], $row['termeknev'], (int)$row['id'], $row['szin'], $row['meret'],
                        (string)$row['vonalkod'],
                    ];
                }
            }
        }
        return [$collisions, $fcmotoCollisions];
    }

    private function getReason(array $group): string
    {
        $reasons = [];
        if (count(array_unique(array_column($group, 'termek_id'))) > 1) {
            $reasons[] = self::REASON_TERMEKCIKKSZAM;
        }
        $szinMeret = array_map(
            fn($row) => $row['termek_id'] . '|' . mb_strtolower(trim($row['szin'])) . '|' . mb_strtolower(trim($row['meret'])),
            $group
        );
        if (count(array_unique($szinMeret)) < count($szinMeret)) {
            $reasons[] = self::REASON_SZINMERET;
        }
        return $reasons ? implode('; ', $reasons) : self::REASON_KOD;
    }

    private function getSharedTermekCikkszamok(): array
    {
        $rows = \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT t.id, t.cikkszam, t.nev, t.inaktiv, t.lathato, x.db,'
            . ' (SELECT COUNT(*) FROM termekvaltozat tv WHERE tv.termek_id = t.id) AS valtozatdb'
            . ' FROM termek t'
            . ' INNER JOIN (SELECT cikkszam, COUNT(*) AS db FROM termek WHERE cikkszam <> "" AND termekfa1_id IN (?)'
            . ' GROUP BY cikkszam HAVING COUNT(*) > 1) x'
            . ' ON x.cikkszam = t.cikkszam'
            . ' WHERE t.termekfa1_id IN (?)'
            . ' ORDER BY x.cikkszam, t.id',
            [$this->termekfaIds, $this->termekfaIds],
            [ArrayParameterType::INTEGER, ArrayParameterType::INTEGER]
        );
        return array_map(fn($row) => [
            $row['cikkszam'], (int)$row['db'], (int)$row['id'], $row['nev'], $this->yes($row['inaktiv']), $this->yes($row['lathato']),
            (int)$row['valtozatdb'],
        ], $rows);
    }

    private function getDuplicateSzinMeret(array $fcmotoValtozatIds): array
    {
        $rows = \mkw\store::getEm()->getConnection()->fetchAllAssociative(
            'SELECT tv.id, tv.termek_id, t.cikkszam, t.nev, s.nev AS szin, m.nev AS meret, tv.vonalkod, tv.lathato, tv.inaktiv, x.db'
            . ' FROM termekvaltozat tv'
            . ' INNER JOIN (SELECT termek_id, szin_id, meret_id, COUNT(*) AS db FROM termekvaltozat'
            . ' WHERE szin_id IS NOT NULL AND meret_id IS NOT NULL GROUP BY termek_id, szin_id, meret_id HAVING COUNT(*) > 1) x'
            . ' ON x.termek_id = tv.termek_id AND x.szin_id = tv.szin_id AND x.meret_id = tv.meret_id'
            . ' INNER JOIN termek t ON t.id = tv.termek_id AND t.termekfa1_id IN (?)'
            . ' INNER JOIN szin s ON s.id = tv.szin_id'
            . ' INNER JOIN meret m ON m.id = tv.meret_id'
            . ' ORDER BY t.cikkszam, tv.termek_id, s.nev, m.nev, tv.id',
            [$this->termekfaIds],
            [ArrayParameterType::INTEGER]
        );
        return array_map(fn($row) => [
            (int)$row['termek_id'], $row['cikkszam'], $row['nev'], $row['szin'], $row['meret'], (int)$row['db'], (int)$row['id'],
            (string)$row['vonalkod'], $this->yes($row['lathato']), $this->yes($row['inaktiv']), $this->yes(isset($fcmotoValtozatIds[$row['id']])),
        ], $rows);
    }

    private function writeSheet(Worksheet $sheet, string $title, array $header, array $rows): void
    {
        $sheet->setTitle($title);
        $sheet->fromArray($header, null, 'A1');
        $rowIndex = 2;
        foreach ($rows as $row) {
            foreach (array_values($row) as $i => $value) {
                // a cikkszám és a vonalkód szöveg marad, különben az Excel számmá alakítaná
                if (is_int($value)) {
                    $sheet->setCellValue([$i + 1, $rowIndex], $value);
                } else {
                    $sheet->setCellValueExplicit([$i + 1, $rowIndex], (string)$value, DataType::TYPE_STRING);
                }
            }
            $rowIndex++;
        }
        $lastColumn = Coordinate::stringFromColumnIndex(count($header));
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:' . $lastColumn . max(1, $rowIndex - 1));
        for ($column = 1; $column <= count($header); $column++) {
            $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
        }
    }

    private function yes($value): string
    {
        return $value ? 'igen' : '';
    }
}
