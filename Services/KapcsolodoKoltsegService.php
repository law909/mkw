<?php

namespace Services;

use Doctrine\ORM\UnitOfWork;
use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\BizonylattetelKapcsolodokoltseg;
use Entities\Kapcsolodokoltseg;

/**
 * A bizonylattételek kapcsolódó költségeinek újraképzése. A tétel sorai a termékhez rendelt
 * költségtörzs másolatai: minden képzés eldobja és újracsinálja őket, mert a mennyiség, a termék
 * és a törzsadatok is változhattak.
 *
 * Két helyről hívható:
 *
 *  - a `BizonylatfejListener` `onFlush`-ából, minden bizonylatmentéskor – ilyenkor a `$uow`-t is át
 *    kell adni, mert a flush közben keletkező entitásokat kézzel kell a UnitOfWork-be számoltatni;
 *  - önálló kérésből (pl. dátumtól dátumig tartó újragenerálás), `$uow` nélkül – ott a hívó a
 *    szokásos módon flushol.
 */
class KapcsolodoKoltsegService
{

    /**
     * @param UnitOfWork|null $uow csak `onFlush` közbeni híváskor
     */
    public static function regenerateBizonylat(Bizonylatfej $bizonylat, ?UnitOfWork $uow = null): void
    {
        // a kapu itt van, nem a hívóban: minden úton ugyanaz a szabály érvényesüljön
        if (!$bizonylat->getBizonylattipus()?->getKellkapcsolodokoltsegetszamolni()) {
            return;
        }
        foreach ($bizonylat->getBizonylattetelek() as $tetel) {
            self::regenerateTetel($tetel, $uow);
        }
    }

    /**
     * @param UnitOfWork|null $uow csak `onFlush` közbeni híváskor
     */
    public static function regenerateTetel(Bizonylattetel $tetel, ?UnitOfWork $uow = null): void
    {
        $em = \mkw\store::getEm();
        foreach ($tetel->getKapcsolodokoltsegek() as $sor) {
            $em->remove($sor);
        }
        $tetel->removeAllKapcsolodokoltseg();

        $termek = $tetel->getTermek();
        if (!$termek) {
            return;
        }
        $md = $em->getClassMetadata(BizonylattetelKapcsolodokoltseg::class);
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
            $em->persist($sor);
            if ($uow) {
                $uow->computeChangeSet($md, $sor);
            }
        }
    }

}
