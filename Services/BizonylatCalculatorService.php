<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Szallitasimod;

class BizonylatCalculatorService
{

    /**
     * A BizonylatfejListener által karbantartott költségtételek termékei: szállítási költség,
     * utánvét költség, kezelési költség, vásárlási utalvány. Ezek egységára nem a termék
     * árlistájából, hanem a szállítási mód / kupon beállításaiból jön, ezért az árak
     * újraszámolásából ki kell hagyni őket (különben 0-ra állna az áruk).
     *
     * @return array a termékid-k, kulcsként is
     */
    private function getKoltsegTermekIdk()
    {
        $ret = [];
        $parok = [
            \mkw\consts::SzallitasiKtgTermek,
            \mkw\consts::UtanvetKtgTermek,
            \mkw\consts::VasarlasiUtalvanyTermek,
        ];
        foreach ($parok as $par) {
            $termekid = \mkw\store::getParameter($par);
            if ($termekid) {
                $ret[$termekid] = $termekid;
            }
        }
        $kezelesi = \mkw\store::getEm()->getRepository(Szallitasimod::class)->getKezelesiKoltsegTermekek();
        foreach ($kezelesi as $termekid) {
            if ($termekid) {
                $ret[$termekid] = $termekid;
            }
        }
        return $ret;
    }

    public function recalcPrice($ids)
    {
        $koltsegtermekek = $this->getKoltsegTermekIdk();
        foreach ($ids as $id) {
            /** @var Bizonylatfej $bizfej */
            $bizfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
            if ($bizfej) {
                \mkw\store::getEm()->beginTransaction();
                try {
                    // Csak a termékek árát számoljuk újra: a szállítási / utánvét költséget a
                    // listener képezné a mentéskor, ami olyan bizonylatra is felvinné, amelyiken
                    // eredetileg nincs (a flag nem perzisztens, alapértéke true).
                    $bizfej->setKellszallitasikoltsegetszamolni(false);
                    /** @var Bizonylattetel $bt */
                    foreach ($bizfej->getBizonylattetelek() as $bt) {
                        if (isset($koltsegtermekek[$bt->getTermekId()])) {
                            continue;
                        }
                        $bt->fillEgysar();
                        $bt->calc();
                        \mkw\store::getEm()->persist($bt);
                    }
                    $bizfej->setNetto(0);
                    \mkw\store::getEm()->persist($bizfej);
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->commit();
                } catch (\Exception $e) {
                    \mkw\store::getEm()->rollback();
                    throw $e;
                }
            }
        }
    }

    public static function kerekitBrutto($ertek, $kerekit, $mincimlet = 0, $keszpenz = false)
    {
        if ($kerekit) {
            $ertek = round($ertek);
        }
        if ($mincimlet && $keszpenz) {
            $ertek = \mkw\store::kerekit($ertek, $mincimlet);
        }
        return $ertek;
    }

    /**
     * @param array $tetelek soronként ['netto', 'afaertek', 'nettohuf', 'afahuf'] kulcsokkal
     *                       (a hiányzó HUF értékek a nem-HUF értékkel egyenlők)
     * @param array $opts ['kerekit'=>bool, 'mincimlet'=>float, 'keszpenz'=>bool, 'defakerekit'=>bool]
     *
     * @return array ['netto','afa','brutto','fizetendo','kerkul','nettohuf','afahuf','bruttohuf']
     */
    public static function calcOsszesenFromTetelek(array $tetelek, array $opts = [])
    {
        $kerekit = !empty($opts['kerekit']);
        $mincimlet = isset($opts['mincimlet']) ? (float)$opts['mincimlet'] : 0;
        $keszpenz = !empty($opts['keszpenz']);
        $defakerekit = !empty($opts['defakerekit']);

        $netto = 0;
        $afa = 0;
        $nettohuf = 0;
        $afahuf = 0;
        foreach ($tetelek as $t) {
            $tnetto = isset($t['netto']) ? (float)$t['netto'] : 0;
            $tafa = isset($t['afaertek']) ? (float)$t['afaertek'] : 0;
            $netto += $tnetto;
            $afa += $tafa;
            $nettohuf += isset($t['nettohuf']) ? (float)$t['nettohuf'] : $tnetto;
            $afahuf += isset($t['afahuf']) ? (float)$t['afahuf'] : $tafa;
        }

        $alapbrutto = self::kerekitBrutto($netto + $afa, $kerekit);
        $brutto = self::kerekitBrutto($netto + $afa, $kerekit, $mincimlet, $keszpenz);
        $bruttohuf = self::kerekitBrutto($nettohuf + $afahuf, $defakerekit);

        return [
            'netto' => $netto,
            'afa' => $afa,
            'brutto' => $brutto,
            'fizetendo' => $brutto,
            'kerkul' => ($mincimlet && $keszpenz) ? ($brutto - $alapbrutto) : 0,
            'nettohuf' => $nettohuf,
            'afahuf' => $afahuf,
            'bruttohuf' => $bruttohuf,
        ];
    }


}