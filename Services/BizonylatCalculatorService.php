<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;

class BizonylatCalculatorService
{

    public function recalcPrice($ids)
    {
        foreach ($ids as $id) {
            /** @var Bizonylatfej $bizfej */
            $bizfej = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
            if ($bizfej) {
                \mkw\store::getEm()->beginTransaction();
                try {
                    /** @var Bizonylattetel $bt */
                    foreach ($bizfej->getBizonylattetelek() as $bt) {
                        $bt->fillEgysar();
                        $bt->kerekitBruttoegysar();
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