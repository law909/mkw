<?php

namespace Controllers;

use Doctrine\ORM\Query\ResultSetMapping;
use Entities\Bizonylatfej;
use Entities\Bizonylattipus;
use Entities\Raktar;
use Entities\Termek;
use Entities\TermekErtesito;
use Entities\TermekFa;
use Entities\TermekValtozat;
use mkwhelpers\FilterDescriptor;

class listaController extends \mkwhelpers\Controller
{

    public function boltbannincsmasholvan()
    {
        $rep = $this->getRepo(TermekValtozat::class);
        $raktarrepo = $this->getRepo(Raktar::class);
        $termekrepo = $this->getRepo(Termek::class);

        $minkeszlet = $this->params->getIntRequestParam('minkeszlet');

        $raktarid = $this->params->getIntRequestParam('raktar');
        $boltraktar = $raktarrepo->find($raktarid);

        $termekfaid = $this->params->getIntRequestParam('termekfa');
        $termekfa = false;
        if ($termekfaid) {
            $termekfa = $this->getRepo(TermekFa::class)->find($termekfaid);
        }

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('raktar_id', 'raktar_id');
        $rsm->addScalarResult('termek_id', 'termek_id');
        $rsm->addScalarResult('termekvaltozat_id', 'termekvaltozat_id');
        $rsm->addScalarResult('keszlet', 'keszlet');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bt.mozgat', '=', true)
            ->addSql('((bt.rontott = 0) OR (bt.rontott IS NULL))')
            ->addFilter('bf.raktar_id', '<>', $raktarid)
            ->addFilter('bf.teljesites', '<=', date(\mkw\store::$DateFormat));
        if ($termekfa) {
            $filter->addFilter(['t.termekfa1karkod', 't.termekfa2karkod', 't.termekfa3karkod'], 'LIKE', $termekfa->getKarkod() . '%');
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
        $res = [];
        foreach ($keszletres as $kesz) {
            /** @var TermekValtozat $valtozat */
            $valtozat = $rep->find($kesz['termekvaltozat_id']);
            if ($valtozat) {
                // a riport a bolt polckészletét nézi, nem a szabad készletet: a foglalás nem számít
                $boltikeszlet = $valtozat->getAvailableStock(
                    datum: null,
                    raktarid: $raktarid,
                    kivevebiz: null,
                    clamp: false,
                    ignoreminkeszlet: true,
                    ignorefoglalas: true
                );
                if ($boltikeszlet <= $minkeszlet) {
                    $raktar = $raktarrepo->find($kesz['raktar_id']);
                    $termek = $termekrepo->find($kesz['termek_id']);

                    $tomb = $termek->toRiport($valtozat);
                    $tomb['raktarnev'] = $raktar->getNev();
                    $tomb['keszlet'] = $kesz['keszlet'];
                    $tomb['boltikeszlet'] = $boltikeszlet;
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

    public function napiJelentes($datum = null, $ig = null, $raktarid = null, $letrehozoid = null)
    {
        if (!$raktarid) {
            $raktarid = 3;
        }
        if (!$datum) {
            $datum = date(\mkw\store::$SQLDateFormat);
        }
        $datum = \mkw\store::convDate($datum);
        if (!$ig) {
            $ig = date(\mkw\store::$SQLDateFormat);
        }
        $ig = \mkw\store::convDate($ig);
        $btrepo = $this->getRepo(Bizonylattipus::class);
        $termekrepo = $this->getRepo(Termek::class);
        $farepo = $this->getRepo(TermekFa::class);
        $focsoportparentid = 1;
        $focsoportok = $farepo->getForParent($focsoportparentid);
        $kiskercimke = \mkw\store::getParameter(\mkw\consts::KiskerCimke);

        $ret = [];
        $raktar = $this->getRepo(Raktar::class)->find($raktarid);
        if ($raktar) {
            $ret['raktarnev'] = $raktar->getNev();
        } else {
            $ret['raktarnev'] = '';
        }

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('f.tipus', 'IN', ['P', 'B'])
            ->addFilter('bf.raktar_id', '=', $raktarid)
            ->addFilter('g.parent_id', '=', $focsoportparentid)
            ->addFilter('bf.bizonylattipus_id', 'IN', ['szamla', 'egyeb', 'keziszamla', 'garancialevel']);

        if ($kiskercimke) {
            $filter->addFilter('pc.cimketorzs_id', '=', $kiskercimke);
        }
        if ($letrehozoid) {
            $filter->addFilter('bf.createdby', '=', $letrehozoid);
        }

        $sums = [];
        foreach ($termekrepo->sumByTermekFaAndFizmodTipus($filter) as $row) {
            $sums[$row['tipus']][$row['termekfa_id']] = $row;
        }

        $ret['napijelentes'] = $this->buildNapijelentesRows($focsoportok, $sums['P'] ?? []);
        $ret['napijelentesnemkp'] = $this->buildNapijelentesRows($focsoportok, $sums['B'] ?? []);

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.bizonylattipus_id', 'IN', ['szamla', 'egyeb', 'keziszamla', 'kivet', 'szallito', 'garancialevel']);
        if ($letrehozoid) {
            $filter->addFilter('bf.createdby', '=', $letrehozoid);
        }

        $nagykerforg = $this->getRepo(Bizonylatfej::class)->calcNagykerForgalom($filter);
        $ret['nagykerforgalom'] = $nagykerforg;

        /*
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.bizonylattipus_id', 'IN', array('szamla', 'egyeb', 'keziszamla', 'kivet', 'garancialevel'));

        $utanvetesforg = $this->getRepo(Bizonylatfej::class)->calcUtanvetesForgalom($filter);
        $ret['utanvetesforgalom'] = $utanvetesforg;
        */

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('bf.teljesites', '>=', $datum)
            ->addFilter('bf.teljesites', '<=', $ig)
            ->addFilter('bf.rontott', '=', false)
            ->addFilter('bf.bizonylattipus_id', 'IN', ['szamla', 'keziszamla']);

        if ($letrehozoid) {
            $filter->addFilter('bf.createdby', '=', $letrehozoid);
        }

        $nemhufforg = $this->getRepo(Bizonylatfej::class)->calcNemHUFForgalom($filter);
        $ret['nemhufforgalom'] = $nemhufforg;

        return $ret;
    }

    private function buildNapijelentesRows($focsoportok, $sums)
    {
        $rows = [];
        foreach ($focsoportok as $csoport) {
            $k = $sums[$csoport['id']] ?? null;
            if (!$k || (!$k['mennyiseg'] && !$k['nettohuf'] && !$k['bruttohuf'])) {
                continue;
            }
            $elem = $csoport;
            $elem['mennyiseg'] = $k['mennyiseg'];
            $elem['netto'] = $k['nettohuf'];
            $elem['brutto'] = $k['bruttohuf'];
            $rows[] = $elem;
        }
        return $rows;
    }

    public function nemkaphatoertesito()
    {
        $sorrend = $this->params->getIntRequestParam('sorrend');
        $order = [];
        switch ($sorrend) {
            case 1:
                $order = ['t.nev' => 'ASC'];
                break;
            case 2:
                $order = ['t.cikkszam' => 'ASC'];
                break;
            case 3:
                // a pont az aliasra rendezést jelenti: a created a MIN(...) select-alias, a _xx.created nincs a GROUP BY-ban
                $order = ['.created' => 'ASC'];
                break;
        }
        $rep = $this->getRepo(TermekErtesito::class);
        $termekek = $rep->getNemkaphatoTermekek($order);
        $lista = [];
        foreach ($termekek as $termek) {
            $termek['karburl'] = \mkw\store::getRouter()->generate(
                'admintermekviewkarb',
                false,
                [],
                ['oper' => 'edit', 'id' => $termek['id']]
            );
            $lista[] = $termek;
        }

        $view = $this->createView('rep_nemkaphatoertesito.tpl');
        $view->setVar('lista', $lista);
        $view->printTemplateResult();
    }
}