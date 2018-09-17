<?php
namespace Controllers;

use mkwhelpers\FilterDescriptor;

class a2aController extends \mkwhelpers\Controller {

    public function processCmd() {
        $pelda = array(
            'cmds' => array(
                'gettermek' => array(
                    'ids' => array(1,2,3,4,5), // vagy
                    'id' => 1062, // vagy
                    'all' => 1
                ),
                'getkeszlet' => array(
                    'ids' => array(1,2,3,4,5), // vagy
                    'id' => 1062
                )
            )
        );

        $result = array();

        $tr = \mkw\store::getEm()->getRepository('Entities\Termek');

        $results = array();

        $data = $this->params->getOriginalStringRequestParam('data');
        $jsondata = json_decode($data, true);
        $cmds = $jsondata['cmds'];
        foreach ($cmds as $cmdkey => $cmd) {

            switch ($cmdkey) {
                case 'gettermek':
                    if (array_key_exists('id', $cmd)) {
                        $termek = $tr->find($cmd['id']);
                        if ($termek) {
                            $termekadat = $termek->toA2a();
                            $results['termek'] = $termekadat;
                        }
                    }
                    elseif (array_key_exists('ids', $cmd)) {
                        $filter = new FilterDescriptor();
                        $filter->addFilter('id', 'IN', $cmd['ids']);
                        $termekek = $tr->getWithJoins($filter);
                        foreach ($termekek as $termek) {
                            $termekadat = $termek->toA2a();
                            $results['termekek'][] = $termekadat;
                        }
                    }
                    elseif (array_key_exists('all', $cmd)) {
                        $termekek = $tr->getWithJoins(null);
                        foreach ($termekek as $termek) {
                            $termekadat = $termek->toA2a();
                            $results['termekek'][] = $termekadat;
                        }
                    }
                    break;
                case 'getkeszlet':
                    if (array_key_exists('id', $cmd)) {
                        $termek = $tr->find($cmd['id']);
                        if ($termek) {
                            $x = array();
                            $x['id'] = $cmd['id'];
                            $valtozatok = $termek->getValtozatok();
                            if ($valtozatok) {
                                foreach ($valtozatok as $valt) {
                                    if ($valt->getXElerheto()) {
                                        $valtadat = array();
                                        $valtadat['id'] = $valt->getId();
                                        $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                                        if ($keszlet < 0) {
                                            $keszlet = 0;
                                        }
                                        $valtadat['keszlet'] = $keszlet;
                                        $x['valtozatok'][] = $valtadat;
                                    }
                                }
                            }
                            $results['keszlet'] = $x;
                        }
                    }
                    elseif (array_key_exists('ids', $cmd)) {
                        $filter = new FilterDescriptor();
                        $filter->addFilter('id', 'IN', $cmd['ids']);
                        $termekek = $tr->getWithJoins($filter);
                        foreach ($termekek as $termek) {
                            $x = array();
                            $x['id'] = $termek->getId();
                            $valtozatok = $termek->getValtozatok();
                            if ($valtozatok) {
                                foreach ($valtozatok as $valt) {
                                    if ($valt->getXElerheto()) {
                                        $valtadat = array();
                                        $valtadat['id'] = $valt->getId();
                                        $keszlet = $valt->getKeszlet() - $valt->getFoglaltMennyiseg();
                                        if ($keszlet < 0) {
                                            $keszlet = 0;
                                        }
                                        $valtadat['keszlet'] = $keszlet;
                                        $x['valtozatok'][] = $valtadat;
                                    }
                                }
                            }
                            $results['keszletek'][] = $x;
                        }
                    }
                    break;
            }
        }

        $result['results'] = $results;
        echo json_encode($result);
    }

}