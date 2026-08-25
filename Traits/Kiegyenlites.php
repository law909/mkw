<?php

namespace Traits;

use Entities\Bizonylatfej;

/**
 * A bizonylatlista pénztár-/bankbizonylat gombjainak két vége.
 *
 * A listasor a gombok URL-jét kéri (kiegyenlitesUrl), a pénztár- ill. bankbizonylat rögzítője
 * pedig a form előtöltendő adatait (kiegyenlitendo). A kettőt a bizonylat sorszáma köti
 * össze: az URL-be csak az kerül, minden más adat a bizonylatból származik, így a rögzítő
 * nem tölthető ki a bizonylattal nem egyező összeggel/partnerrel.
 */
trait Kiegyenlites
{

    /** a "Kiegyenlít" gomb kérésparaméterének neve (a kiegyenlítendő bizonylat sorszáma) */
    private static $kiegyenlitesParam = 'kiegyenlit';

    /** fizetésimód-típus -> a hozzá tartozó rögzítő útvonala */
    private static $kiegyenlitesUtvonalak = [
        'P' => 'adminpenztarbizonylatfejviewkarb',
        'B' => 'adminbankbizonylatfejviewkarb',
    ];

    /**
     * A bizonylat kiegyenlítésére nyíló rögzítő URL-je, vagy '' ha a bizonylathoz nem való gomb.
     *
     * A rontott és a szülővel bíró stornó bizonylat mindkét gombot kizárja: a folyószámlájuk a
     * szülőn van, a getEgyenleg() is 0-t ad rájuk.
     *
     * @param \Entities\Bizonylatfej $bizonylat
     * @param float $egyenleg a listán mutatott egyenleg (pozitív, ha kiegyenlítetlen)
     * @param string|null $tipus 'P' = pénztárbizonylat, 'B' = bankbizonylat; üresen a bizonylat
     *                           fizetési módja dönt – ez a „Kiegyenlít" gomb
     *
     * @return string
     */
    protected function kiegyenlitesUrl($bizonylat, $egyenleg, $tipus = null)
    {
        // a pénztár- és bankbizonylat útvonalai csak bankpénztáras deployen élnek
        if (!\mkw\store::isBankpenztar() || !$bizonylat || !$bizonylat->getPenztmozgat()
            || $bizonylat->getRontott() || $bizonylat->isStornoGyerek()) {
            return '';
        }
        if (!$tipus) {
            // a "Kiegyenlít" gombnak csak nyitott egyenlegnél van értelme; a két rögzítő gomb
            // kiegyenlített bizonylaton is kell (utólagos befizetés, visszafizetés)
            if (abs($egyenleg * 1) < 0.005) {
                return '';
            }
            $tipus = $bizonylat->getFizmod()?->getTipus();
        }
        if (!isset(self::$kiegyenlitesUtvonalak[$tipus])) {
            return '';
        }
        return \mkw\store::getRouter()->generate(
            self::$kiegyenlitesUtvonalak[$tipus],
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
     * A listasor gombjaival indított rögzítő előtöltendő adatai, vagy null, ha a kérés nem
     * onnan jött.
     *
     * Kiegyenlített bizonylatról is jöhet a kérés (a két rögzítő gomb ott is látszik) – ilyenkor
     * az összeg 0, a többi adat viszont kitöltődik, a felhasználó csak az összeget írja be.
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
        // ugyanaz a szűrés, mint a gombok kiadásánál: kézzel összerakott kérésre se töltsön elő
        if (!$bizonylat || !$bizonylat->getPenztmozgat()
            || $bizonylat->getRontott() || $bizonylat->isStornoGyerek()) {
            return null;
        }
        // ugyanaz az előjelezés, mint a bizonylatlistán: pozitív, ha még kiegyenlítetlen
        $egyenleg = $bizonylat->getEgyenleg() * -1 * $bizonylat->getIrany();
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
