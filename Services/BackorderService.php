<?php

namespace Services;

use Entities\Bizonylatfej;
use Entities\Bizonylatstatusz;
use Entities\TermekFa;

class BackorderService
{
    private function szabadKeszlet(
        \Entities\Bizonylattetel $tetel,
        Bizonylatfej $biz,
        $nominkeszlet,
        $nominkeszletkat
    ): float|bool {
        /** @var \Entities\Termek $t */
        $t = $tetel->getTermek();
        if (!$t || !$t->getMozgat()) {
            return false;
        }
        $v = $tetel->getTermekvaltozat();
        if ($nominkeszlet && $t->isInTermekKategoria($nominkeszletkat)) {
            if ($v) {
                $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($biz->getId());
            } else {
                $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($biz->getId());
            }
        } elseif ($v) {
            $keszlet = $v->getKeszlet() - $v->getFoglaltMennyiseg($biz->getId()) - $v->calcMinboltikeszlet();
        } else {
            $keszlet = $t->getKeszlet() - $t->getFoglaltMennyiseg($biz->getId()) - $t->getMinboltikeszlet();
        }
        return max($keszlet, 0);
    }

    public function backOrder($id)
    {
        $regibiz = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->find($id);
        if ($regibiz) {
            $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
            $nominkeszletkat = \mkw\store::getEm()->getRepository(TermekFa::class)->find(
                \mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat)
            )?->getKarkod();
            $teljesitheto = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(
                \mkw\store::getParameter(\mkw\consts::BizonylatStatuszTeljesitheto)
            );
            $backorder = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
            \mkw\store::getEm()->beginTransaction();
            try {
                $ujdb = 0;
                $regidb = 0;
                /** @var \Entities\Bizonylattetel $regitetel */
                foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                    $keszlet = $this->szabadKeszlet($regitetel, $regibiz, $nominkeszlet, $nominkeszletkat);
                    if ($keszlet === false) {
                        continue;
                    }
                    if ($keszlet < $regitetel->getMennyiseg()) {
                        $ujdb++;
                        if ($keszlet > 0) {
                            $regidb++;
                        }
                    } else {
                        $regidb++;
                    }
                }
                if ($regidb == 0 || $ujdb == 0) {
                    $result = 0;
                    if ($ujdb == 0) {
                        $regibiz->setBizonylatstatusz($teljesitheto);
                        foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                            //$regitetel->fillEgysar();
                            $regitetel->calc();
                            \mkw\store::getEm()->persist($regitetel);
                        }
                    } elseif ($regidb == 0) {
                        $regibiz->setBizonylatstatusz($backorder);
                        $result = 1;
                    }
                    \mkw\store::getEm()->persist($regibiz);
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->commit();
                    return ['refresh' => $result];
                } else {
                    $ujbiz = new \Entities\Bizonylatfej();
                    $ujbiz->duplicateFrom($regibiz);
                    $ujbiz->clearId();
                    $ujbiz->clearCreated();
                    $ujbiz->clearLastmod();
                    $ujbiz->setKelt();
                    $ujbiz->setBizonylatstatusz($backorder);
                    /** @var \Entities\Bizonylattetel $regitetel */
                    foreach ($regibiz->getBizonylattetelek() as $regitetel) {
                        $keszlet = $this->szabadKeszlet($regitetel, $regibiz, $nominkeszlet, $nominkeszletkat);
                        if ($keszlet === false) {
                            $regitetel->calc();
                            \mkw\store::getEm()->persist($regitetel);
                            continue;
                        }
                        if ($keszlet < $regitetel->getMennyiseg()) {
                            $ujtetel = new \Entities\Bizonylattetel();
                            $ujtetel->duplicateFrom($regitetel);
                            $ujtetel->clearCreated();
                            $ujtetel->clearLastmod();
                            $ujtetel->setMennyiseg($regitetel->getMennyiseg() - $keszlet);
                            $ujtetel->calc();
                            $ujbiz->addBizonylattetel($ujtetel);
                            \mkw\store::getEm()->persist($ujtetel);
                            if ($keszlet <= 0) {
                                $regibiz->removeBizonylattetel($regitetel);
                                \mkw\store::getEm()->remove($regitetel);
                            } else {
                                $regitetel->setMennyiseg($keszlet);
                                //$regitetel->fillEgysar();
                                $regitetel->calc();
                                \mkw\store::getEm()->persist($regitetel);
                            }
                        } else {
                            //$regitetel->fillEgysar();
                            $regitetel->calc();
                            \mkw\store::getEm()->persist($regitetel);
                        }
                    }
                    $regibiz->setBizonylatstatusz($teljesitheto);
                    \mkw\store::getEm()->persist($regibiz);
                    \mkw\store::getEm()->persist($ujbiz);
                    \mkw\store::getEm()->flush();
                    \mkw\store::getEm()->commit();
                    return ['refresh' => 1];
                }
            } catch (\Exception $e) {
                \mkw\store::getEm()->rollback();
                throw $e;
            }
        } else {
            return ['refresh' => 0];
        }
    }

    public function getTeljesithetoBackorderLista()
    {
        $ret = [];
        $backorder = \mkw\store::getEm()->getRepository(Bizonylatstatusz::class)->find(\mkw\store::getParameter(\mkw\consts::BizonylatStatuszBackorder));
        if ($backorder) {
            $nominkeszlet = \mkw\store::getParameter(\mkw\consts::NoMinKeszlet);
            $nominkeszletkat = \mkw\store::getEm()->getRepository(TermekFa::class)->find(
                \mkw\store::getParameter(\mkw\consts::NoMinKeszletTermekkat)
            )?->getKarkod();

            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('bizonylatstatusz', '=', $backorder);
            $filter->addFilter('bizonylattipus', '', ['megrendeles', 'webshopbiz']);
            $filter->addFilter('rontott', '=', false);
            $filter->addFilter('hibas', '=', false);
            $fejek = \mkw\store::getEm()->getRepository(Bizonylatfej::class)->getWithTetelek($filter, ['hatarido' => 'ASC']);
            if ($fejek) {
                /** @var \Entities\Bizonylatfej $fej */
                foreach ($fejek as $fej) {
                    $vankeszlet = false;
                    $tetelek = $fej->getBizonylattetelek();
                    /** @var \Entities\Bizonylattetel $tetel */
                    foreach ($tetelek as $tetel) {
                        /** @var \Entities\TermekValtozat $termekv */
                        $termekv = $tetel->getTermekvaltozat();
                        if ($termekv) {
                            if ($nominkeszlet && $tetel->getTermek()?->isInTermekKategoria($nominkeszletkat)) {
                                if ($termekv->getKeszlet() - $termekv->getFoglaltMennyiseg() > 0) {
                                    $vankeszlet = true;
                                    break;
                                }
                            } elseif ($termekv->getKeszlet() - $termekv->getFoglaltMennyiseg() - $termekv->calcMinboltikeszlet() > 0) {
                                $vankeszlet = true;
                                break;
                            }
                        } else {
                            $termek = $tetel->getTermek();
                            if ($termek) {
                                if ($nominkeszlet && $termek->isInTermekKategoria($nominkeszletkat)) {
                                    if ($termek->getKeszlet() - $termek->getFoglaltMennyiseg() > 0) {
                                        $vankeszlet = true;
                                        break;
                                    }
                                } elseif ($termek->getKeszlet() - $termek->getFoglaltMennyiseg() - $termek->getMinboltikeszlet() > 0) {
                                    $vankeszlet = true;
                                    break;
                                }
                            }
                        }
                    }
                    if ($vankeszlet) {
                        $ret[] = [
                            'id' => $fej->getId(),
                            'kelt' => $fej->getKeltStr(),
                            'hatarido' => $fej->getHataridoStr(),
                            'partnernev' => $fej->getPartnernev(),
                            'printurl' => \mkw\store::getRouter()->generate('adminmegrendelesfejprint', false, [], [
                                'id' => $fej->getId()
                            ]),
                            'editurl' => \mkw\store::getRouter()->generate('adminmegrendelesfejviewkarb', false, [], [
                                'id' => $fej->getId(),
                                'oper' => 'edit'
                            ])
                        ];
                    }
                }
            }
        }
        return $ret;
    }


}