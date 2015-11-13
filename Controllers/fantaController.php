<?php

namespace Controllers;

use mkw\store;

class fantaController extends \mkwhelpers\MattableController {

    public function show() {
        $this->view = $this->getTemplateFactory()->createMainView('fanta.tpl');
        $this->view->printTemplateResult(true);
    }

    public function doit() {
        $ret = array(
            'ec' => 599,
            'em' => 'Internal server error #599.'
        );

        $ev = $this->params->getIntRequestParam('ev');
        $bizszam = $this->params->getIntRequestParam('tol');
        $pw = $this->params->getStringRequestParam('pw');
        if (md5($pw . \mkw\Store::getAdminSalt()) == \mkw\Store::getParameter(\mkw\consts::Tulajcrc)) {
            $bt = $this->getRepo('Entities\Bizonylattipus')->find('szamla');
            $ujbt = $this->getRepo('Entities\Bizonylattipus')->find('egyeb');
            if ($bt && $ujbt) {
                $szamlaszam = \mkw\Store::createBizonylatszam($bt->getAzonosito(), $ev, $bizszam);

                $filter = new \mkwhelpers\FilterDescriptor();
                $filter
                    ->addFilter('bizonylattipus', '=', $bt)
                    ->addFilter('id', '>=', $szamlaszam);

                $fixfilter = new \mkwhelpers\FilterDescriptor();
                $fixfilter->addFilter('fix', '=', true);

                $fixdb = $this->getRepo('Entities\Bizonylatfej')->getCount($filter->merge($fixfilter));

                if ($fixdb == 0) {
                    $szamlak = $this->getRepo('Entities\Bizonylatfej')->getAll($filter, array());

                    $ret = array(
                        'ec' => 597,
                        'em' => 'Internal server error #597.'
                    );

                    $this->getEm()->transactional(function ($em) use ($szamlak) {
                        foreach ($szamlak as $szamla) {
                            $szamla->removeParbizonylatfej();
                            $szamla->setPenztmozgat(false);
                            $em->persist($szamla);
                            foreach ($szamla->getBizonylattetelek() as $biztetel) {
                                $biztetel->removeParbizonylattetel();
                                $em->persist($biztetel);
                            }
                        }
                    });

                    $this->getEm()->transactional(function ($em) use ($szamlak, $ujbt) {
                        $sorszam = 0;
                        foreach ($szamlak as $szamla) {
                            if ($szamla->getStorno()) {
                                $szamla->setMegjegyzes('');
                                $em->persist($szamla);
                            }
                            //$uj = clone $szamla;
                            $uj = new \Entities\Bizonylatfej();
                            $uj->duplicateFrom($szamla);
                            $uj->clearId();
                            $uj->clearCreated();
                            $uj->clearLastmod();
                            $uj->setBizonylattipus($ujbt);
                            $sorszam = $uj->generateId($sorszam);
                            $sorszam++;
                            foreach ($szamla->getBizonylattetelek() as $biztetel) {
                                if ($biztetel->getStorno()) {
                                    //$em->persist($biztetel);
                                }
                                //$ujtetel = clone $biztetel;
                                $ujtetel = new \Entities\Bizonylattetel();
                                $uj->addBizonylattetel($ujtetel);
                                $ujtetel->duplicateFrom($biztetel);
                                $ujtetel->clearCreated();
                                $ujtetel->clearLastmod();

                                foreach ($biztetel->getTranslations() as $trans) {
                                    $ujtrans = clone $trans;
                                    $ujtetel->addTranslation($ujtrans);
                                    $em->persist($ujtrans);
                                }

                                $em->persist($ujtetel);
                            }
                            $em->remove($szamla);
                            $em->persist($uj);
                        }
                    });

                    $ret = array(
                        'ec' => 0,
                        'em' => 'Ready.'
                    );
                }
                else {
                    $ret = array(
                        'ec' => 596,
                        'em' => 'Internal server error #596 FIX.'
                    );
                }
            }
            else {
                $ret = array(
                    'ec' => 598,
                    'em' => 'Internal server error #598.'
                );
            }
        }
        echo json_encode($ret);
    }

    public function mese() {
        if (havejog(99)) {
            $bizszam = $this->params->getStringRequestParam('b');
            /** @var \Entities\Bizonylatfej $szamla */
            $szamla = $this->getRepo('Entities\Bizonylatfej')->find($bizszam);
            if ($szamla && !$szamla->getStorno() && !$szamla->getStornozott()
                && !$szamla->getFix() && !$szamla->getMese()) {
                $ujbt = $this->getRepo('Entities\Bizonylattipus')->find('egyeb');

                $this->getEm()->transactional(function ($em) use ($szamla, $ujbt) {
                    $uj = new \Entities\Bizonylatfej();
                    $uj->duplicateFrom($szamla);
                    $uj->clearId();
                    $uj->clearCreated();
                    $uj->clearLastmod();
                    $uj->setNyomtatva(false);
                    $uj->setFix(false);
                    $uj->setBizonylattipus($ujbt);
                    $uj->generateId();
                    foreach ($szamla->getBizonylattetelek() as $biztetel) {
                        $ujtetel = new \Entities\Bizonylattetel();
                        $uj->addBizonylattetel($ujtetel);
                        $ujtetel->duplicateFrom($biztetel);
                        $ujtetel->clearCreated();
                        $ujtetel->clearLastmod();

                        foreach ($biztetel->getTranslations() as $trans) {
                            $ujtrans = clone $trans;
                            $ujtetel->addTranslation($ujtrans);
                            $em->persist($ujtrans);
                        }

                        $em->persist($ujtetel);
                    }
                    $em->persist($uj);
                    $szamla->setNyomtatva(false);
                    $szamla->setMese(true);
                    $szamla->setPenztmozgat(false);
                    /** @var \Entities\Bizonylattetel $bt */
                    foreach ($szamla->getBizonylattetelek() as $bt) {
                        $bt->setMozgat();
                        $bt->setMese(true);
                        $em->persist($bt);
                    }
                    $em->persist($szamla);
                });
            }
        }
    }
}
