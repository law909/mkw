<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Termek;
use Entities\TermekValtozat;

class BizonylatConcatService
{

    public function concat($ids)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($ids) {
            $filter->addFilter('id', 'IN', $ids);
        }
        $fejek = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->getWithJoins($filter, []);
        $partnerek = [];
        $rendelesidk = [];
        /** @var Bizonylatfej $fej */
        foreach ($fejek as $fej) {
            $partnerek[$fej->getPartnerId()] = $fej->getPartnernev();
            $rendelesidk[] = $fej->getId();
        }
        if (count($partnerek) == 1) {
            $termekek = [];
            foreach ($fejek as $fej) {
                /** @var Bizonylattetel $tetel */
                foreach ($fej->getBizonylattetelek() as $tetel) {
                    $kulcs = $tetel->getTermekId() . '-' . $tetel->getTermekvaltozatId() . '-' . $tetel->getNettoegysar();
                    if (!isset($termekek[$kulcs])) {
                        $termekek[$kulcs] = [
                            'termekid' => $tetel->getTermekId(),
                            'termekvaltozatid' => $tetel->getTermekvaltozatId(),
                            'afaid' => $tetel->getAfaId(),
                            'vtszid' => $tetel->getVtszId(),
                            'nettoegysar' => $tetel->getNettoegysar(),
                            'nettoegysarhuf' => $tetel->getNettoegysarhuf(),
                            'mennyiseg' => $tetel->getMennyiseg(),
                            'enettoegysar' => $tetel->getEnettoegysar(),
                            'enettoegysarhuf' => $tetel->getEnettoegysarhuf(),
                            'kedvezmeny' => $tetel->getKedvezmeny(),
                        ];
                    } else {
                        $termekek[$kulcs]['mennyiseg'] += $tetel->getMennyiseg();
                    }
                }
            }
            \mkw\store::getEm()->beginTransaction();
            try {
                $vantetel = false;
                $fej = $fejek[0];
                $ujfej = new Bizonylatfej();
                $ujfej->duplicateFrom($fej);
                $ujfej->clearId();
                $ujfej->setKelt('');
                $ujfej->setTeljesites('');
                $ujfej->setEsedekesseg('');
                $ujfej->setHatarido('');
                $ujfej->removeBizonylatstatusz();
                $ujfej->setBelsomegjegyzes(implode(', ', $rendelesidk));
                foreach ($termekek as $termek) {
                    $biztetel = new Bizonylattetel();
                    $ujfej->addBizonylattetel($biztetel);
                    $biztetel->setPersistentData();
                    $biztetel->setTermek(\mkw\store::getEm()->getRepository(Termek::class)->find($termek['termekid']));
                    $biztetel->setTermekvaltozat(\mkw\store::getEm()->getRepository(TermekValtozat::class)->find($termek['termekvaltozatid']));
                    $biztetel->setFoglal();
                    $biztetel->setVtsz($termek['vtszid']);
                    $biztetel->setAfa($termek['afaid']);
                    $biztetel->setMennyiseg($termek['mennyiseg']);

                    $biztetel->setEnettoegysar($termek['enettoegysar']);
                    $biztetel->setEnettoegysarhuf($termek['enettoegysarhuf']);
                    $biztetel->setKedvezmeny($termek['kedvezmeny']);
                    $biztetel->setNettoegysar($termek['nettoegysar']);
                    $biztetel->setNettoegysarhuf($termek['nettoegysarhuf']);
                    $biztetel->calc();
                    \mkw\store::getEm()->persist($biztetel);
                    $vantetel = true;
                }
                if ($vantetel) {
                    $ujfej->calcOsszesen();
                    \mkw\store::getEm()->persist($ujfej);
                    \mkw\store::getEm()->flush();
                }
                \mkw\store::getEm()->commit();
            } catch (\Exception $e) {
                \mkw\store::getEm()->rollback();
                throw $e;
            }
        }
    }
}