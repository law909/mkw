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

                $filter = array();
                $filter['fields'][] = 'bizonylattipus';
                $filter['clauses'][] = '=';
                $filter['values'][] = $bt;

                $filter['fields'][] = 'id';
                $filter['clauses'][] = '>=';
                $filter['values'][] = $szamlaszam;

                $szamlak = $this->getRepo('Entities\Bizonylatfej')->getAll($filter,array());

                $ret = array(
                    'ec' => 597,
                    'em' => 'Internal server error #597.'
                );

                $this->getEm()->transactional(function($em) use ($szamlak, $ujbt) {
                    $sorszam = 0;
                    foreach($szamlak as $szamla) {
                        if ($szamla->getStorno()) {
                            $szamla->removeParbizonylatfej();
                            $szamla->setMegjegyzes('');
                            $em->persist($szamla);
                        }
                        $uj = clone $szamla;
                        $uj->clearId();
                        $uj->clearCreated();
                        $uj->clearLastmod();
                        $uj->setBizonylattipus($ujbt);
                        $sorszam = $uj->generateId($sorszam);
                        $sorszam++;
                        foreach($szamla->getBizonylattetelek() as $biztetel) {
                            if ($biztetel->getStorno()) {
                                $biztetel->removeParbizonylattetel();
                                $em->persist($biztetel);
                            }
                            $ujtetel = clone $biztetel;
                            $ujtetel->clearCreated();
                            $ujtetel->clearLastmod();
                            $uj->addBizonylattetel($ujtetel);
                            $em->persist($ujtetel);
                        }
                        $em->remove($szamla);
                        $em->persist($uj);
//                        $em->flush();
                    }
                });

                $ret = array(
                    'ec' => 0,
                    'em' => 'Ready.'
                );
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
}
