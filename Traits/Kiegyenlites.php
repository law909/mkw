<?php

namespace Traits;

use Entities\Bizonylatfej;

/**
 * A bizonylatlista "Kiegyenlít" gombjának két vége.
 *
 * A listasor a gomb URL-jét kéri (kiegyenlitesUrl), a pénztár- ill. bankbizonylat rögzítője
 * pedig a form előtöltendő adatait (kiegyenlitendo). A kettőt a bizonylat sorszáma köti
 * össze: az URL-be csak az kerül, minden más adat a bizonylatból származik, így a rögzítő
 * nem tölthető ki a bizonylattal nem egyező összeggel/partnerrel.
 */
trait Kiegyenlites
{

    /** a "Kiegyenlít" gomb kérésparaméterének neve (a kiegyenlítendő bizonylat sorszáma) */
    private static $kiegyenlitesParam = 'kiegyenlit';

    /**
     * A bizonylat kiegyenlítésére nyíló rögzítő URL-je, vagy '' ha a bizonylathoz nem való
     * gomb: nincs nyitott egyenlege, vagy a fizetési módja nem készpénz ('P') / bank ('B').
     *
     * @param \Entities\Bizonylatfej $bizonylat
     * @param float $egyenleg a listán mutatott egyenleg (pozitív, ha kiegyenlítetlen)
     *
     * @return string
     */
    protected function kiegyenlitesUrl($bizonylat, $egyenleg)
    {
        // a pénztár- és bankbizonylat útvonalai csak bankpénztáras deployen élnek
        if (!\mkw\store::isBankpenztar() || !$bizonylat || (abs($egyenleg * 1) < 0.005)) {
            return '';
        }
        switch ($bizonylat->getFizmod()?->getTipus()) {
            case 'P':
                $utvonal = 'adminpenztarbizonylatfejviewkarb';
                break;
            case 'B':
                $utvonal = 'adminbankbizonylatfejviewkarb';
                break;
            default:
                return '';
        }
        return \mkw\store::getRouter()->generate(
            $utvonal,
            false,
            [],
            [
                'id' => 0,
                'oper' => 'add',
                self::$kiegyenlitesParam => $bizonylat->getId()
            ]
        );
    }

    /**
     * A "Kiegyenlít" gombbal indított rögzítő előtöltendő adatai, vagy null, ha a kérés nem
     * onnan jött (illetve a bizonylat időközben kiegyenlítődött).
     *
     * @param string $jogcimparameter a rögzítő automatikus jogcímének paraméterneve
     *
     * @return array|null
     */
    protected function kiegyenlitendo($jogcimparameter = \mkw\consts::AutoPenztarbizonylatJogcim)
    {
        $id = $this->params->getStringRequestParam(self::$kiegyenlitesParam);
        if (!$id) {
            return null;
        }
        /** @var \Entities\Bizonylatfej $bizonylat */
        $bizonylat = $this->getRepo(Bizonylatfej::class)->find($id);
        if (!$bizonylat) {
            return null;
        }
        // ugyanaz az előjelezés, mint a bizonylatlistán: pozitív, ha még kiegyenlítetlen
        $egyenleg = $bizonylat->getEgyenleg() * -1 * $bizonylat->getIrany();
        if (abs($egyenleg) < 0.005) {
            return null;
        }
        // A pénzmozgás iránya a bizonylatéval ellentétes (a számlára befizetés jön), a
        // negatív egyenleg pedig megfordítja, mert a túlfizetést visszafizetjük. Így az
        // összeg mindig pozitív marad, az irányt a fej (pénztár) vagy a tétel (bank) hordozza.
        $irany = $bizonylat->getIrany() * -1 * ($egyenleg < 0 ? -1 : 1);

        return [
            'bizonylat' => $bizonylat->getId(),
            'partnerid' => $bizonylat->getPartnerId(),
            'partnernev' => $bizonylat->getPartnernev(),
            'irany' => $irany,
            'osszeg' => round(abs($egyenleg), 4),
            'keltstr' => date(\mkw\store::$DateFormat),
            'esedekessegstr' => $bizonylat->getEsedekessegStr() ?: $bizonylat->getKeltStr(),
            'jogcimid' => \mkw\store::getParameter($jogcimparameter)
        ];
    }
}
