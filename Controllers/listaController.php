<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use mkwhelpers\FilterDescriptor;

class listaController extends \mkwhelpers\Controller {

    public function boltbannincsmasholvan() {
        $rep = $this->getRepo('Entities\TermekValtozat');
        $raktarrepo = $this->getRepo('Entities\Raktar');
        $termekrepo = $this->getRepo('Entities\Termek');

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
            ->addFilter('bt.mozgat', '=', 1)
            ->addFilter('bf.raktar_id', '<>', $raktarid)
            ->addFilter('bf.teljesites', '<=', date(\mkw\Store::$DateFormat));
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
        $view->setVar('datum', date(\mkw\Store::convDate(\mkw\Store::$DateFormat)));
        if ($termekfa) {
            $view->setVar('termekcsoport', $termekfa->getNev());
        }
        $view->setVar('lista', $res);
        $view->printTemplateResult();
    }

    public function napiJelentes($datum = null) {
        if (!$datum) {
            $datum = date(\mkw\Store::$SQLDateFormat);
        }
        $datum = \mkw\Store::convDate($datum);
        $btrepo = $this->getRepo('Entities\Bizonylattipus');
        $termekrepo = $this->getRepo('Entities\Termek');
        $farepo = $this->getRepo('Entities\TermekFa');
        $focsoportok = $farepo->getForParent(1);
        $kiskercimke = \mkw\Store::getParameter(\mkw\consts::KiskerCimke);
        $ret = array();
        foreach($focsoportok as $csoport) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter
                ->addFilter('bt.mozgat', '=', 1)
                ->addFilter('bf.teljesites', '=', $datum)
                ->addFilter('bf.rontott', '=', false)
                ->addFilter('f.tipus', '=', 'P')
                ->addFilter(array('t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'), 'LIKE', $csoport['karkod'] . '%')
                ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla'));

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
                $ret[] = $elem;
            }
        }
        return $ret;
    }

    public function teljesitmenyJelentes() {
        $ma = new \DateTime();
        $eveleje = new \DateTime(date('Y') . '-01-01');
        $calcma = new \DateTime();
        $elozoma = $calcma->sub(new \DateInterval('P1Y'));
        $nap = $ma->diff($eveleje);
        $nap = $nap->days;
        $elozoeveleje = new \DateTime(date('Y') * 1 - 1 . '-01-01');
        /** @var \Entities\BizonylatfejRepository $bfrepo */
        $bfrepo = $this->getRepo('Entities\Bizonylatfej');
        $ret = array();

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('kelt', '>=', $elozoeveleje)
            ->addFilter('kelt', '<=', $elozoma)
            ->addFilter('bizonylattipus', '=', 'megrendeles');
        $ret['elozomegrendelesdb'] = $bfrepo->getCount($filter);
        $ret['elozomegrendelespernapdb'] = $ret['elozomegrendelesdb'] / $nap;

        $filter->clear();
        $filter
            ->addFilter('kelt', '>=', $eveleje)
            ->addFilter('kelt', '<=', $ma)
            ->addFilter('bizonylattipus', '=', 'megrendeles');
        $ret['megrendelesdb'] = $bfrepo->getCount($filter);
        $ret['megrendelespernapdb'] = $ret['megrendelesdb'] / $nap;

        $ret['megrendelesvaltdb'] = $ret['megrendelesdb'] / $ret['elozomegrendelesdb'] * 100;

        $filter = new FilterDescriptor();
        $filter
            ->addFilter('kelt', '>=', $elozoeveleje)
            ->addFilter('kelt', '<=', $elozoma)
            ->addFilter('bizonylattipus', '=', 'szamla');
        $ret['elozoszamladb'] = $bfrepo->getCount($filter);
        $ret['elozoszamlapernapdb'] = $ret['elozoszamladb'] / $nap;

        $filter->clear();
        $filter
            ->addFilter('kelt', '>=', $eveleje)
            ->addFilter('kelt', '<=', $ma)
            ->addFilter('bizonylattipus', '=', 'szamla');
        $ret['szamladb'] = $bfrepo->getCount($filter);
        $ret['szamlapernapdb'] = $ret['szamladb'] / $nap;

        $ret['szamlavaltdb'] = $ret['szamladb'] / $ret['elozoszamladb'] * 100;

        $ret['elozoteljratadb'] = $ret['elozoszamladb'] / $ret['elozomegrendelesdb'] * 100;
        $ret['teljratadb'] = $ret['szamladb'] / $ret['megrendelesdb'] * 100;

        $filter->clear();
        $filter
            ->addFilter('kelt', '>=', $eveleje)
            ->addFilter('kelt', '<=', $ma)
            ->addFilter('bizonylattipus', '=', 'megrendeles');
        $sum = $bfrepo->calcSumWithJoins($filter);
        $ret['megrendeleshuf'] = $sum['netto'];
        $ret['megrendelespernaphuf'] = $ret['megrendeleshuf'] / $nap;

        $filter->clear();
        $filter
            ->addFilter('kelt', '>=', $elozoeveleje)
            ->addFilter('kelt', '<=', $elozoma)
            ->addFilter('bizonylattipus', '=', 'megrendeles');
        $sum = $bfrepo->calcSumWithJoins($filter);
        $ret['elozomegrendeleshuf'] = $sum['netto'];
        $ret['elozomegrendelespernaphuf'] = $ret['elozomegrendeleshuf'] / $nap;

        $ret['megrendelesvalthuf'] = $ret['megrendeleshuf'] / $ret['elozomegrendeleshuf'] * 100;

        $filter->clear();
        $filter
            ->addFilter('kelt', '>=', $eveleje)
            ->addFilter('kelt', '<=', $ma)
            ->addFilter('bizonylattipus', '=', 'szamla');
        $sum = $bfrepo->calcSumWithJoins($filter);
        $ret['szamlahuf'] = $sum['netto'];
        $ret['szamlapernaphuf'] = $ret['szamlahuf'] / $nap;

        $filter->clear();
        $filter
            ->addFilter('kelt', '>=', $elozoeveleje)
            ->addFilter('kelt', '<=', $elozoma)
            ->addFilter('bizonylattipus', '=', 'szamla');
        $sum = $bfrepo->calcSumWithJoins($filter);
        $ret['elozoszamlahuf'] = $sum['netto'];
        $ret['elozoszamlapernaphuf'] = $ret['elozoszamlahuf'] / $nap;

        $ret['szamlavalthuf'] = $ret['szamlahuf'] / $ret['elozoszamlahuf'] * 100;

        $ret['elozoteljratahuf'] = $ret['elozoszamlahuf'] / $ret['elozomegrendeleshuf'] * 100;
        $ret['teljratahuf'] = $ret['szamlahuf'] / $ret['megrendeleshuf'] * 100;

        $ret['elozomegrendelesatlaghuf'] = $ret['elozomegrendeleshuf'] / $ret['elozomegrendelesdb'];
        $ret['elozoszamlaatlaghuf'] = $ret['elozoszamlahuf'] / $ret['elozoszamladb'];

        $ret['megrendelesatlaghuf'] = $ret['megrendeleshuf'] / $ret['megrendelesdb'];
        $ret['szamlaatlaghuf'] = $ret['szamlahuf'] / $ret['szamladb'];

        $ret['elozoteljrataatlaghuf'] = $ret['elozoszamlaatlaghuf'] / $ret['elozomegrendelesatlaghuf'] * 100;
        $ret['teljrataatlaghuf'] = $ret['szamlaatlaghuf'] / $ret['megrendelesatlaghuf'] * 100;

        $ret['megrendelesatlagvalthuf'] = $ret['megrendelesatlaghuf'] / $ret['elozomegrendelesatlaghuf'] * 100;
        $ret['szamlaatlagvalthuf'] = $ret['szamlaatlaghuf'] / $ret['elozoszamlaatlaghuf'] * 100;


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
            $termek['karburl'] = \mkw\Store::getRouter()->generate('admintermekviewkarb', false, array(),
                array('oper' => 'edit', 'id' => $termek['id']));
            $lista[] = $termek;
        }

        $view = $this->createView('rep_nemkaphatoertesito.tpl');
        $view->setVar('lista', $lista);
        $view->printTemplateResult();
    }
}