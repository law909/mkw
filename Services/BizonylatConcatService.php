<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylattetel;
use Entities\Termek;
use Entities\TermekValtozat;

class BizonylatConcatService
{

    public function concat($ids, $rontEredetiek = false)
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
                    if ($tetel->getTermekegyediazonosito() || $tetel->getTermek()?->getKellegyediazonosito()) {
                        // az egyedi azonosítós tétel mennyisége csak 1 vagy -1 lehet, ezért nem vonjuk össze
                        $kulcs = 'egyedi-' . $tetel->getId();
                    }
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
                            'termekegyediazonosito' => $tetel->getTermekegyediazonosito(),
                            'termeknev' => $tetel->getTermeknev(),
                            'megjegyzes' => [],
                            'megjegyzes2' => [],
                            'hatarido' => $tetel->getHatarido(),
                            'vasarlasdatum' => $tetel->getVasarlasdatum(),
                            'gyujtomennyiseg' => 0,
                            'sordobozmennyiseg' => 0,
                        ];
                    } else {
                        $termekek[$kulcs]['mennyiseg'] += $tetel->getMennyiseg();
                    }
                    $termekek[$kulcs]['gyujtomennyiseg'] += $tetel->getGyujtomennyiseg();
                    $termekek[$kulcs]['sordobozmennyiseg'] += $tetel->getSordobozmennyiseg();
                    foreach (['megjegyzes' => $tetel->getMegjegyzes(), 'megjegyzes2' => $tetel->getMegjegyzes2()] as $mezo => $szoveg) {
                        $szoveg = trim((string)$szoveg);
                        if ($szoveg !== '' && !in_array($szoveg, $termekek[$kulcs][$mezo], true)) {
                            $termekek[$kulcs][$mezo][] = $szoveg;
                        }
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
                    $biztetel->setTermekegyediazonosito($termek['termekegyediazonosito']);
                    // a setTermek() a termék aktuális nevét írta be, az eredeti tétel kézzel átírt neve kell
                    $biztetel->setTermeknev($termek['termeknev']);
                    $biztetel->setMegjegyzes(implode('; ', $termek['megjegyzes']) ?: null);
                    $biztetel->setMegjegyzes2(implode('; ', $termek['megjegyzes2']) ?: null);
                    // null-lal a setterek a mai dátumot írnák be
                    if ($termek['hatarido']) {
                        $biztetel->setHatarido(clone $termek['hatarido']);
                    }
                    if ($termek['vasarlasdatum']) {
                        $biztetel->setVasarlasdatum(clone $termek['vasarlasdatum']);
                    }
                    $biztetel->setGyujtomennyiseg($termek['gyujtomennyiseg']);
                    $biztetel->setSordobozmennyiseg($termek['sordobozmennyiseg']);
                    $biztetel->setNettoegysar($termek['nettoegysar']);
                    $biztetel->setNettoegysarhuf($termek['nettoegysarhuf']);
                    $biztetel->calc();
                    \mkw\store::getEm()->persist($biztetel);
                    $vantetel = true;
                }
                if ($vantetel) {
                    $ujfej->calcOsszesen();
                    \mkw\store::getEm()->persist($ujfej);
                    if ($rontEredetiek) {
                        foreach ($fejek as $eredeti) {
                            $eredeti->setKellszallitasikoltsegetszamolni(false);
                            $eredeti->setRontott(true);
                            \mkw\store::getEm()->persist($eredeti);
                        }
                    }
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