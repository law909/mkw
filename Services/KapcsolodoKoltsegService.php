<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\BizonylattetelKapcsolodokoltseg;
use Entities\Kapcsolodokoltseg;

/**
 * A bizonylattételek kapcsolódó költségeinek újragenerálása. A tétel sorai a termékhez rendelt
 * költségtörzs másolatai: minden mentéskor eldobjuk és újraképezzük őket, mert a mennyiség, a
 * termék és a törzsadatok is változhattak.
 */
class KapcsolodoKoltsegService
{

    public static function regenerateBizonylat(Bizonylatfej $bizonylat): void
    {
        foreach ($bizonylat->getBizonylattetelek() as $tetel) {
            self::regenerateTetel($tetel);
        }
    }

    public static function regenerateTetel(Bizonylattetel $tetel): void
    {
        // orphanRemoval viszi el a régi sorokat; a törlésnek a persist előtt kell megtörténnie
        $tetel->removeAllKapcsolodokoltseg();
        $termek = $tetel->getTermek();
        if (!$termek) {
            return;
        }
        $mennyiseg = (float)$tetel->getMennyiseg();
        /** @var Kapcsolodokoltseg $koltseg */
        foreach ($termek->getKapcsolodokoltsegek() as $koltseg) {
            $sor = new BizonylattetelKapcsolodokoltseg();
            $sor->setKapcsolodokoltseg($koltseg);
            $sor->setNev($koltseg->getNev());
            $sor->setCsoport($koltseg->getCsoport());
            $sor->setSzamitasalap($koltseg->getSzamitasalap());
            $sor->setAr($koltseg->getAr());
            $sor->setNavfeladando($koltseg->getNavfeladando());
            $sor->setSzamitasalapertek($koltseg->getSzamitasalapErtek($termek));
            $sor->setErtek($koltseg->calcErtek($termek) * $mennyiseg);
            $tetel->addKapcsolodokoltseg($sor);
            \mkw\store::getEm()->persist($sor);
        }
    }

}
