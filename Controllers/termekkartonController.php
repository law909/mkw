<?php

namespace Controllers;

use Entities\Bizonylattetel;
use Entities\Partner;
use Entities\Termek;
use mkwhelpers\FilterDescriptor;

class termekkartonController extends \mkwhelpers\Controller
{

    /**
     * A karton önállóan is meghívható: `id` nélkül a terméket a képernyőn kell kiválasztani
     * (autocomplete + változat), vagy egy bizonylattételen szereplő egyedi azonosítóból
     * megkerestetni ({@see egyediAzonositoKereses()}).
     */
    public function view()
    {
        $termekid = $this->params->getIntRequestParam('id');
        /** @var \Entities\Termek|null $termek */
        $termek = $termekid ? $this->getRepo(Termek::class)->find($termekid) : null;

        $view = $this->createView('termekkarton.tpl');

        $view->setVar('pagetitle', t('Termék karton'));
        $view->setVar('datumtipus', 'teljesites');
        // termékválasztó akkor kell, ha nem konkrét termékre nyitották meg
        $view->setVar('termekvalaszto', !$termek);
        $view->setVar('termekid', $termek ? $termek->getId() : 0);
        $view->setVar('termeknev', $termek ? $termek->getNev() : '');
        $view->setVar('cikkszam', $termek ? $termek->getCikkszam() : '');
        $view->setVar('keszletetmozgat', $termek ? $termek->getMozgat() : true);
        // választós módban mindig látszik: a kiválasztott termék még lehet egyedi azonosítós
        $view->setVar('kellegyediazonosito', $termek ? $termek->getKellegyediazonosito() : true);
        $tc = new termekController();
        $view->setVar('valtozatlista', $termek ? $tc->getValtozatList($termekid, null) : []);
        $rc = new raktarController();
        $view->setVar('raktarlista', $rc->getSelectList());
        $partner = new partnerController();
        $view->setVar('partnerlist', $partner->getSelectList());
        $pcc = new partnercimkekatController();
        $view->setVar('cimkekat', $pcc->getWithCimkek());

        $view->printTemplateResult(false);
    }

    public function refresh()
    {
        $termekid = $this->params->getIntRequestParam('termekid');
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        $mozgat = $this->params->getIntRequestParam('mozgat');
        $rontott = $this->params->getIntRequestParam('rontott');
        $raktarid = $this->params->getIntRequestParam('raktarid');
        $partnerid = $this->params->getIntRequestParam('partnerid');
        $partnercimkefilter = $this->params->getArrayRequestParam('partnercimkefilter');
        $datumtipus = $this->params->getStringRequestParam('datumtipus');
        switch ($datumtipus) {
            case 'kelt':
            case 'teljesites':
            case 'esedekesseg':
                $datumtipus = 'bf.' . $datumtipus;
                break;
            default:
                $datumtipus = 'bf.kelt';
                break;
        }
        $datumtolstr = $this->params->getStringRequestParam('datumtol');
        $datumigstr = $this->params->getStringRequestParam('datumig');
        $egyediazonosito = trim($this->params->getStringRequestParam('egyediazonosito'));

        $nyitofilter = new FilterDescriptor();
        $filter = new FilterDescriptor();
        $nyitofilter->addFilter('bt.termek', '=', $termekid);
        $filter->addFilter('bt.termek', '=', $termekid);
        if ($valtozatid) {
            $nyitofilter->addFilter('bt.termekvaltozat', '=', $valtozatid);
            $filter->addFilter('bt.termekvaltozat', '=', $valtozatid);
        }
        if ($egyediazonosito !== '') {
            $nyitofilter->addFilter('bt.termekegyediazonosito', '=', $egyediazonosito);
            $filter->addFilter('bt.termekegyediazonosito', '=', $egyediazonosito);
        }
        if ($datumtolstr) {
            $nyitofilter->addFilter($datumtipus, '<', $datumtolstr);
            $filter->addFilter($datumtipus, '>=', $datumtolstr);
        }
        if ($datumigstr) {
            $filter->addFilter($datumtipus, '<=', $datumigstr);
        }
        switch ($mozgat) {
            case 1:
                $nyitofilter->addFilter('bt.mozgat', '=', true);
                $filter->addFilter('bt.mozgat', '=', true);
                break;
            case 2:
                $nyitofilter->addFilter('bt.mozgat', '=', false);
                $filter->addFilter('bt.mozgat', '=', false);
                break;
        }
        switch ($rontott) {
            case 1:
                break;
            case 2:
                $nyitofilter->addFilter('bf.rontott', '<>', true);
                $filter->addFilter('bf.rontott', '<>', true);
                break;
        }
        if ($raktarid) {
            $nyitofilter->addFilter('bf.raktar', '=', $raktarid);
            $filter->addFilter('bf.raktar', '=', $raktarid);
        }

        $partnerkodok = $this->getRepo(Partner::class)->getByCimkek($partnercimkefilter);
        if ($partnerid) {
            $nyitofilter->addFilter('bf.partner', '=', $partnerid);
            $filter->addFilter('bf.partner', '=', $partnerid);
        } elseif ($partnerkodok) {
            $nyitofilter->addFilter('bf.partner', 'IN', $partnerkodok);
            $filter->addFilter('bf.partner', 'IN', $partnerkodok);
        }

        if ($datumtolstr) {
            $nyito = $this->getRepo(Termek::class)->calcKeszlet($nyitofilter);
            $nyito = $nyito[0];
        } else {
            $nyito = ['mennyiseg' => 0, 'nettohuf' => 0, 'bruttohuf' => 0];
        }
        $tetelek = $this->getRepo(Termek::class)->getKarton($filter, [$datumtipus => 'ASC']);
        $kartontetelek = [];
        foreach ($tetelek as $tetel) {
            $r = [
                'tetel' => $tetel->toLista(),
                'fej' => $tetel->getBizonylatfej()->toLista()
            ];
            $kartontetelek[] = $r;
        }

        $view = $this->createView('termekkartontetel.tpl');
        $view->setVar('maintheme', \mkw\store::getTheme());
        $view->setVar('nyito', $nyito['mennyiseg']);
        $view->setVar('kartontetelek', $kartontetelek);
        $view->printTemplateResult();
    }

    /**
     * Egy termék változatai a termékválasztó után – a bizonylattétel változatlistájával azonos
     * tartalom, csak json-ban, mert itt egy sima select-et kell újratölteni.
     */
    public function valtozatLista()
    {
        $tc = new termekController();
        echo json_encode($tc->getValtozatList($this->params->getIntRequestParam('termekid'), null));
    }

    /**
     * Termék és változat megkeresése egy bizonylattételen szereplő egyedi azonosítóból. Ugyanaz
     * az azonosító több tételen is szerepelhet (bevét, majd eladás), de ugyanarra a termékre –
     * ezért az elsőt vesszük.
     */
    public function egyediAzonositoKereses()
    {
        header('Content-Type: application/json; charset=utf-8');
        $azonosito = trim($this->params->getStringRequestParam('egyediazonosito'));
        if ($azonosito === '') {
            $this->jsonFail(t('Adja meg az egyedi azonosítót.'));
            return;
        }
        /** @var \Entities\Bizonylattetel|null $tetel */
        $tetel = $this->getRepo(Bizonylattetel::class)->findOneBy(['termekegyediazonosito' => $azonosito]);
        $termek = $tetel?->getTermek();
        if (!$termek) {
            $this->jsonFail(sprintf(t('Nincs "%s" egyedi azonosítójú bizonylattétel.'), $azonosito));
            return;
        }
        $tc = new termekController();
        echo json_encode([
            'ok' => true,
            'termekid' => $termek->getId(),
            'termeknev' => $termek->getNev(),
            'cikkszam' => $termek->getCikkszam(),
            'valtozatid' => $tetel->getTermekvaltozatId(),
            'valtozatlista' => $tc->getValtozatList($termek->getId(), $tetel->getTermekvaltozatId()),
        ]);
    }

    public function egyediAzonositoLista()
    {
        $termekid = $this->params->getIntRequestParam('termekid');
        $valtozatid = $this->params->getIntRequestParam('valtozatid');
        $term = trim($this->params->getStringRequestParam('term'));
        $ret = [];
        if ($termekid) {
            $ret = $this->getRepo(Bizonylattetel::class)
                ->getEgyediAzonositoLista($termekid, $valtozatid, $term);
        }
        echo json_encode($ret);
    }

}