<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class listaController extends \mkwhelpers\Controller {

    public function boltbannincsmasholvan() {
        $rep = $this->getRepo('Entities\TermekValtozat');
        $raktarrepo = $this->getRepo('Entities\Raktar');
        $termekrepo = $this->getRepo('Entities\Termek');

        $minkeszlet = $this->params->getIntRequestParam('minkeszlet');

        $raktarid = $this->params->getIntRequestParam('raktar');
        $boltraktar = $raktarrepo->find($raktarid);

        $termekfaid = $this->params->getIntRequestParam('termekfa');
        $termekfa = false;
        if ($termekfaid) {
            $termekfa = $this->getRepo('Entities\TermekFa')->find($termekfaid);
        }

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('raktar_id', 'raktar_id');
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
        $rsm->addScalarResult('keszlet', 'keszlet');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bt.mozgat', '=', true)
            ->addFilter('bt.rontott', '<>', true)
            ->addFilter('bf.raktar_id', '<>', $raktarid)
            ->addFilter('bf.teljesites', '<=', date(\mkw\store::$DateFormat));
        if ($termekfa) {
            $filter->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $termekfa->getKarkod() . '%');
        }

        $sql = 'SELECT bf.raktar_id, bt.termek_id, bt.termekvaltozat_id, SUM(bt.mennyiseg*bt.irany) AS keszlet FROM bizonylattetel bt '
            . 'LEFT OUTER JOIN bizonylatfej bf ON (bt.bizonylatfej_id=bf.id) '
            . 'LEFT OUTER JOIN termek t ON (bt.termek_id=t.id) '
            . $rep->getFilterString($filter)
            . 'GROUP BY bf.raktar_id, bt.termek_id, bt.termekvaltozat_id '
            . 'HAVING keszlet>0';

        $q = $this->getEm()->createNativeQuery($sql, $rsm);
        $params = $rep->getQueryParameters($filter);
        $q->setParameters($params);

        $keszletres = $q->getScalarResult();
        $res = array();
        foreach ($keszletres as $kesz) {
            $valtozat = $rep->find($kesz['termekvaltozat_id']);
            if ($valtozat) {
                $boltikeszlet = $valtozat->getKeszlet(null, $raktarid);
                if ($boltikeszlet <= $minkeszlet) {
                    $raktar = $raktarrepo->find($kesz['raktar_id']);
                    $termek = $termekrepo->find($kesz['termek_id']);

                    $tomb = $termek->toRiport($valtozat);
                    $tomb['raktarnev'] = $raktar->getNev();
                    $tomb['keszlet'] = $kesz['keszlet'];
                    $res[] = $tomb;
                }
            }
        }

        if ($res) {
            foreach ($res as $key => $row) {
                $cikkszam[$key] = $row['cikkszam'];
                $nev[$key] = $row['nev'];
                $id[$key] = $row['id'];
                $valtozatnev[$key] = $row['valtozatnev'];
                $valtozatid[$key] = $row['valtozatid'];
            }
            array_multisort($cikkszam, SORT_ASC, $nev, SORT_ASC, $id, SORT_ASC, $valtozatnev, SORT_ASC, $valtozatid, SORT_ASC, $res);
        }

        $view = $this->createView('rep_boltbannincsmasholvan.tpl');
        $view->setVar('raktarnev', $boltraktar->getNev());
        $view->setVar('datum', date(\mkw\store::convDate(\mkw\store::$DateFormat)));
        if ($termekfa) {
            $view->setVar('termekcsoport', $termekfa->getNev());
        }
        $view->setVar('lista', $res);
        $view->printTemplateResult();
    }

    public function napiJelentes($datum = null, $ig = null) {
        if (!$datum) {
            $datum = date(\mkw\store::$SQLDateFormat);
        }
        $datum = \mkw\store::convDate($datum);
        if (!$ig) {
            $ig = date(\mkw\store::$SQLDateFormat);
        }
        $ig = \mkw\store::convDate($ig);
        $btrepo = $this->getRepo('Entities\Bizonylattipus');
        $termekrepo = $this->getRepo('Entities\Termek');
        $farepo = $this->getRepo('Entities\TermekFa');
        $focsoportok = $farepo->getForParent(1);
        $kiskercimke = \mkw\store::getParameter(\mkw\consts::KiskerCimke);

        $ret = array();

        $napijelentes = array();
        foreach($focsoportok as $csoport) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter
                ->addFilter('bf.teljesites', '>=', $datum)
                ->addFilter('bf.teljesites', '<=', $ig)
                ->addFilter('bf.rontott', '=', false)
                ->addFilter('f.tipus', '=', 'P')
                ->addFilter('bf.mese', '=', false)
                ->addFilter('bf.raktar_id', '=', 3)
                ->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $csoport['karkod'] . '%')
                ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla', 'garancialevel'));

            if ($kiskercimke) {
                $filter->addFilter('pc.cimketorzs_id', '=', $kiskercimke);
            }

            $k = $termekrepo->calcNapijelentes($filter);
            $k = $k[0];
            if ($k['mennyiseg'] || $k['nettohuf'] || $k['bruttohuf']) {
                $elem = $csoport;
                $elem['mennyiseg'] = $k['mennyiseg'];
                $elem['netto'] = $k['nettohuf'];
                $elem['brutto'] = $k['bruttohuf'];
                $napijelentes[] = $elem;
            }
        }
        $ret['napijelentes'] = $napijelentes;

        $napijelentes = array();
        foreach($focsoportok as $csoport) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter
                ->addFilter('bf.teljesites', '>=', $datum)
                ->addFilter('bf.teljesites', '<=', $ig)
                ->addFilter('bf.rontott', '=', false)
                ->addFilter('f.tipus', '=', 'B')
                ->addFilter('bf.mese', '=', false)
                ->addFilter('bf.raktar_id', '=', 3)
                ->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $csoport['karkod'] . '%')
                ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla', 'garancialevel'));

            if ($kiskercimke) {
                $filter->addFilter('pc.cimketorzs_id', '=', $kiskercimke);
            }

            $k = $termekrepo->calcNapijelentes($filter);
            $k = $k[0];
            if ($k['mennyiseg'] || $k['nettohuf'] || $k['bruttohuf']) {
                $elem = $csoport;
                $elem['mennyiseg'] = $k['mennyiseg'];
                $elem['netto'] = $k['nettohuf'];
                $elem['brutto'] = $k['bruttohuf'];
                $napijelentes[] = $elem;
            }
        }
        $ret['napijelentesnemkp'] = $napijelentes;

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.mese', '=', false)
            ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla', 'kivet', 'szallito', 'garancialevel'));

        $nagykerforg = $this->getRepo('Entities\Bizonylatfej')->calcNagykerForgalom($filter);
        $ret['nagykerforgalom'] = $nagykerforg;

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.mese', '=', false)
            ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla', 'kivet', 'garancialevel'));

        $utanvetesforg = $this->getRepo('Entities\Bizonylatfej')->calcUtanvetesForgalom($filter);
        $ret['utanvetesforgalom'] = $utanvetesforg;

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.mese', '=', false)
            ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla', 'kivet', 'garancialevel'));

        $nemhufforg = $this->getRepo('Entities\Bizonylatfej')->calcNemHUFForgalom($filter);
        $ret['nemhufforgalom'] = $nemhufforg;

        return $ret;
    }

    public function nemkaphatoertesito() {
        $sorrend = $this->params->getIntRequestParam('sorrend');
        switch ($sorrend) {
            case 1:
                $order = array('t.nev' => 'ASC');
                break;
            case 2:
                $order = array('t.cikkszam' => 'ASC');
                break;
            case 3:
                $order = array('created' => 'ASC');
                break;
        }
        $rep = $this->getRepo('Entities\TermekErtesito');
        $termekek = $rep->getNemkaphatoTermekek($order);
        $lista = array();
        foreach ($termekek as $termek) {
            $termek['karburl'] = \mkw\store::getRouter()->generate('admintermekviewkarb', false, array(),
                array('oper' => 'edit', 'id' => $termek['id']));
            $lista[] = $termek;
        }

        $view = $this->createView('rep_nemkaphatoertesito.tpl');
        $view->setVar('lista', $lista);
        $view->printTemplateResult();
    }
}