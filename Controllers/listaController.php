<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;

class listaController extends \mkwhelpers\Controller {

    public function boltbannincsmasholvan() {
        $rep = $this->getRepo('Entities\TermekValtozat');
        $raktarrepo = $this->getRepo('Entities\Raktar');
        $termekrepo = $this->getRepo('Entities\Termek');

        $raktarid = $this->params->getIntRequestParam('raktar');
        $boltraktar = $raktarrepo->find($raktarid);

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('raktar_id', 'raktar_id');
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
        $rsm->addScalarResult('keszlet', 'keszlet');

        $filter = array();
        $filter['fields'][] = 'bt.mozgat';
        $filter['clauses'][] = '=';
        $filter['values'][] = '1';
        $filter['fields'][] = 'bf.raktar_id';
        $filter['clauses'][] = '<>';
        $filter['values'][] = $raktarid;
        $filter['fields'][] = 'bf.teljesites';
        $filter['clauses'][] = '<=';
        $filter['values'][] = date(\mkw\Store::$DateFormat);

        $sql = 'SELECT bf.raktar_id, bt.termek_id, bt.termekvaltozat_id, SUM(bt.mennyiseg*bt.irany) AS keszlet FROM bizonylattetel bt '
            . 'LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id) '
            . $rep->getFilterString($filter)
            . 'GROUP BY bf.raktar_id, bt.termek_id, bt.termekvaltozat_id '
            . 'HAVING keszlet>0';

        $q = $this->getEm()->createNativeQuery($sql, $rsm);
        $params = $rep->getQueryParameters($filter);
        $q->setParameters($params);

        $keszletres = $q->getScalarResult();
        $res = array();
        foreach($keszletres as $kesz) {
            $valtozat = $rep->find($kesz['termekvaltozat_id']);
            if ($valtozat) {
                $boltikeszlet = $valtozat->getKeszlet(null, $raktarid);
                if ($boltikeszlet <= 0) {
                    $raktar = $raktarrepo->find($kesz['raktar_id']);
                    $termek = $termekrepo->find($kesz['termek_id']);

                    $tomb = $termek->toRiport($valtozat);
                    $tomb['raktarnev'] = $raktar->getNev();
                    $tomb['keszlet'] = $kesz['keszlet'];
                    $res[] = $tomb;
                }
            }
        }

        foreach($res as $key => $row) {
            $cikkszam[$key] = $row['cikkszam'];
            $nev[$key] = $row['nev'];
            $id[$key] = $row['id'];
            $valtozatnev[$key] = $row['valtozatnev'];
            $valtozatid[$key] = $row['valtozatid'];
        }
        array_multisort($cikkszam, SORT_ASC, $nev, SORT_ASC, $id, SORT_ASC, $valtozatnev, SORT_ASC, $valtozatid, SORT_ASC, $res);

        $view = $this->createView('rep_boltbannincsmasholvan.tpl');
        $view->setVar('raktarnev', $boltraktar->getNev());
        $view->setVar('datum', date(\mkw\Store::convDate(\mkw\Store::$DateFormat)));
        $view->setVar('lista', $res);
        $view->printTemplateResult();
    }
}