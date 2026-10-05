<?php

namespace Services;

use Entities\Arsav;
use Entities\TermekValtozat;
use Entities\TermekValtozatAr;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * A galad "product export – végleges ár eltérések" XLSX első lapja: ahol a J oszlop nettó ára eltér a termék
 * kisker (HUF) nettó árától, az a változat saját kisker árává válik. A változatot a vonalkód (E), ennek
 * hiányában a cikkszám (D) azonosítja.
 */
class GaladValtozatArImportService
{
    private const ARSAVNEV = 'Kisker.ár';
    private const VALUTANEMNEV = 'HUF';
    private const KOTEG = 200;

    /**
     * @return array{uj: int, modositott: int, valtozatlan: int, egyezik: int, kimaradt: array<int, array{sor: int, cikkszam: string, vonalkod: string, ok: string}>}
     */
    public function import(string $filepath): array
    {
        $em = \mkw\store::getEm();
        $arsav = $em->getRepository(Arsav::class)->findOneBy(['nev' => self::ARSAVNEV]);
        $valutanem = $em->getRepository(Valutanem::class)->findOneBy(['nev' => self::VALUTANEMNEV]);
        if (!$arsav || !$valutanem) {
            throw new \RuntimeException(sprintf(t('Nincs "%s" ársáv vagy "%s" valutanem.'), self::ARSAVNEV, self::VALUTANEMNEV));
        }

        $reader = IOFactory::createReader(IOFactory::identify($filepath));
        $reader->setReadDataOnly(true);
        $excel = $reader->load($filepath);
        $rows = $excel->getSheet(0)->toArray(null, true, false, true);
        $excel->disconnectWorksheets();
        // a kulcsok az Excel sorszámai, az üzenetekben is ezek kellenek
        $fejlec = $rows[1] ?? [];
        unset($rows[1]);
        if (mb_strtolower(trim((string)($fejlec['J'] ?? ''))) !== 'ár') {
            throw new \RuntimeException(t('Az első lap J oszlopának fejléce nem "Ár", ez nem az ár eltérés fájl.'));
        }

        $vonalkodMap = $this->loadKodMap('vonalkod');
        $cikkszamMap = $this->loadKodMap('cikkszam');
        $termekArak = $em->getConnection()->fetchAllKeyValue(
            'SELECT termek_id, netto FROM termekar WHERE arsav_id = ? AND valutanem_id = ? ORDER BY id DESC',
            [$arsav->getId(), $valutanem->getId()]
        );

        $eredmeny = ['uj' => 0, 'modositott' => 0, 'valtozatlan' => 0, 'egyezik' => 0, 'kimaradt' => []];
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            $db = 0;
            foreach ($rows as $sorszam => $row) {
                $cikkszam = $this->kod($row['D'] ?? '');
                $vonalkod = $this->kod($row['E'] ?? '');
                $ar = $row['J'] ?? null;
                if ($cikkszam === '' && $vonalkod === '' && ($ar === null || $ar === '')) {
                    continue;
                }
                $kimarad = function ($ok) use (&$eredmeny, $sorszam, $cikkszam, $vonalkod) {
                    $eredmeny['kimaradt'][] = ['sor' => $sorszam, 'cikkszam' => $cikkszam, 'vonalkod' => $vonalkod, 'ok' => $ok];
                };
                if (!is_numeric($ar)) {
                    $kimarad(t('nincs ár a J oszlopban'));
                    continue;
                }
                $valtozatid = ($vonalkod !== '' ? ($vonalkodMap[$vonalkod] ?? null) : null) ?? ($cikkszam !== '' ? ($cikkszamMap[$cikkszam] ?? null) : null);
                /** @var TermekValtozat|null $valtozat */
                $valtozat = $valtozatid ? $em->find(TermekValtozat::class, $valtozatid) : null;
                if (!$valtozat || !$valtozat->getTermek()) {
                    $kimarad(t('nincs ilyen vonalkódú vagy cikkszámú változat'));
                    continue;
                }
                $netto = round((float)$ar, 2);
                $termekNetto = $termekArak[$valtozat->getTermek()->getId()] ?? null;
                if ($termekNetto !== null && round((float)$termekNetto, 2) == $netto) {
                    $eredmeny['egyezik']++;
                    continue;
                }
                $sajat = null;
                foreach ($valtozat->getArak() as $va) {
                    if ($va->getArsavId() === $arsav->getId() && $va->getValutanemId() === $valutanem->getId()) {
                        $sajat = $va;
                        break;
                    }
                }
                if ($sajat && round((float)$sajat->getNetto(), 2) == $netto) {
                    $eredmeny['valtozatlan']++;
                    continue;
                }
                if (!$sajat) {
                    $sajat = new TermekValtozatAr();
                    $sajat->setTermekvaltozat($valtozat);
                    $sajat->setArsav($arsav);
                    $sajat->setValutanem($valutanem);
                    $valtozat->getArak()->add($sajat);
                    $eredmeny['uj']++;
                } else {
                    $eredmeny['modositott']++;
                }
                $sajat->setNetto($netto);
                $em->persist($sajat);
                if (++$db % self::KOTEG === 0) {
                    $em->flush();
                }
            }
            $em->flush();
            $conn->commit();
        } catch (\Throwable $e) {
            $conn->rollBack();
            throw $e;
        }
        return $eredmeny;
    }

    /** a számként tárolt vonalkód tudományos alak nélkül */
    private function kod($val): string
    {
        return trim(is_float($val) ? sprintf('%.0F', $val) : (string)$val);
    }

    /** kód => változat id; azonos kódnál a kisebb id nyer */
    private function loadKodMap(string $mezo): array
    {
        $sorok = \mkw\store::getEm()
            ->createQuery('SELECT v.id, v.' . $mezo . ' AS kod FROM Entities\TermekValtozat v ORDER BY v.id ASC')
            ->getScalarResult();
        $map = [];
        foreach ($sorok as $sor) {
            $kod = trim((string)$sor['kod']);
            if ($kod !== '' && !isset($map[$kod])) {
                $map[$kod] = (int)$sor['id'];
            }
        }
        return $map;
    }
}
