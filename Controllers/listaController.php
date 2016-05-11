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

    public function teljesitmenyJelentes($tol = null, $ig = null) {

        function per($a, $b) {
            $a = $a * 1;
            $b = $b * 1;
            if ($b) {
                return $a / $b;
            }
            return 0;
        }

        /** @var \Entities\BizonylatfejRepository $bfrepo */
        $bfrepo = $this->getRepo('Entities\Bizonylatfej');

        if (!$tol) {
            $evtol = \mkw\store::getParameter(\mkw\consts::TeljesitmenyKezdoEv, 2014);
            $hotol = '01';
            $naptol = '01';
            $tol = new \DateTime($evtol . '-' . $hotol . '-' . $naptol);
        }
        else {
            $tol = new \DateTime($tol);
            $evtol = $tol->format('Y') * 1;
            $hotol = $tol->format('m');
            $naptol = $tol->format('d');
        }

        if (!$ig) {
            $ig = new \DateTime();
            $evig = date('Y') * 1;
            $hoig = date('m');
            $napig = date('d');
        }
        else {
            $ig = new \DateTime($ig);
            $evig = $ig->format('Y') * 1;
            $hoig = $ig->format('m');
            $napig = $ig->format('d');
        }

        $evek = array();
        for ($ev = $evtol; $ev <= $evig; $ev++) {
            $evek[] = array(
                'eleje' => (string)$ev . '-' . $hotol . '-' . $naptol,
                'vege' => (string)$ev . '-' . $hoig . '-' . $napig
            );
        }

        $elejenaphoz = new \DateTime($evtol . '-' . $hoig . '-' . $napig);
        $nap = $elejenaphoz->diff($tol);
        $nap = $nap->days + 1;

        $sqls = array();
        foreach ($evek as $ev) {
            $sqls[] = '((_xx.kelt>="' . $ev['eleje'] . '") AND (_xx.kelt<="' . $ev['vege'] . '"))';
        }

        $filter = new FilterDescriptor();
        $filter->addFilter('bizonylattipus_id', '=', 'megrendeles');
        $filter->addSql(implode(' OR ', $sqls));
        $sorok = $bfrepo->calcTeljesitmeny($filter);
        $adat = array();
        foreach ($sorok as $sor) {
            $ev = $sor['ev'] * 1;
            $a = array();
            $a['ev'] = $ev;
            $a['megrendelesdb'] = $sor['db'] * 1;
            $a['megrendelesdbpernap'] = per($sor['db'], $nap);
            $a['megrendelesnetto'] = $sor['netto'] * 1;
            $a['megrendelesnettopernap'] = per($sor['netto'], $nap);
            $a['megrendelesnettoperdb'] = per($sor['netto'], $sor['db']);
            if (array_key_exists($ev - 1, $adat)) {
                $a['megrendelesdbvalt'] = per($a['megrendelesdb'], $adat[$ev - 1]['megrendelesdb']) * 100;
                $a['megrendelesnettovalt'] = per($a['megrendelesnetto'], $adat[$ev - 1]['megrendelesnetto']) * 100;
                $a['megrendelesnettoperdbvalt'] = per($a['megrendelesnettoperdb'], $adat[$ev - 1]['megrendelesnettoperdb']) * 100;
            }
            else {
                $a['megrendelesdbvalt'] = 0;
                $a['megrendelesnettovalt'] = 0;
                $a['megrendelesnettoperdbvalt'] = 0;
            }
            $adat[$ev] = $a;
        }
        $filter->clear();
        $filter->addFilter('bizonylattipus_id', '=', 'szamla');
        $filter->addSql(implode(' OR ', $sqls));
        $sorok = $bfrepo->calcTeljesitmeny($filter);
        foreach ($sorok as $sor) {
            $ev = $sor['ev'] * 1;
            $adat[$ev]['szamladb'] = $sor['db'] * 1;
            $adat[$ev]['teljratadb'] = per($adat[$ev]['szamladb'], $adat[$ev]['megrendelesdb']) * 100;
            $adat[$ev]['szamladbpernap'] = per($sor['db'], $nap);
            $adat[$ev]['szamlanetto'] = $sor['netto'] * 1;
            $adat[$ev]['teljratanetto'] = per($adat[$ev]['szamlanetto'], $adat[$ev]['megrendelesnetto']) * 100;
            $adat[$ev]['szamlanettopernap'] = per($sor['netto'], $nap);
            $adat[$ev]['szamlanettoperdb'] = per($sor['netto'], $sor['db']);
            $adat[$ev]['teljratanettoperdb'] = per($adat[$ev]['szamlanettoperdb'], $adat[$ev]['megrendelesnettoperdb']) * 100;
            if (array_key_exists($ev - 1, $adat)) {
                $adat[$ev]['szamladbvalt'] = per($adat[$ev]['szamladb'], $adat[$ev - 1]['szamladb']) * 100;
                $adat[$ev]['szamlanettovalt'] = per($adat[$ev]['szamlanetto'], $adat[$ev - 1]['szamlanetto']) * 100;
                $adat[$ev]['szamlanettoperdbvalt'] = per($adat[$ev]['szamlanettoperdb'], $adat[$ev - 1]['szamlanettoperdb']) * 100;
            }
            else {
                $adat[$ev]['szamladbvalt'] = 0;
                $adat[$ev]['szamlanettovalt'] = 0;
                $adat[$ev]['szamlanettoperdbvalt'] = 0;
            }
        }
        return $adat;
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