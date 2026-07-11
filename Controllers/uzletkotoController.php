<?php

namespace Controllers;

use Entities\Arsav;
use Entities\Fizmod;
use Entities\Partner;
use Entities\Szallitasimod;
use Entities\Uzletkoto;
use Entities\Valutanem;

class uzletkotoController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Uzletkoto::class);
        $this->setKarbFormTplName('uzletkotokarbform.tpl');
        $this->setKarbTplName('uzletkotokarb.tpl');
        $this->setListBodyRowTplName('uzletkotolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_uzletkoto');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Uzletkoto();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['valutanemnev'] = $t->getPartnervalutanem()?->getNev();
        $x['szallitasimodnev'] = $t->getPartnerszallitasimod()?->getNev();
        $x['fizmodnev'] = $t->getPartnerfizmod()?->getNev();
        $x['fouzletkotonev'] = ($t->getFouzletkoto() ? $t->getFouzletkoto()->getNev() : '');
        return $x;
    }

    /*
     *  EntityController->save() hívja, ezért kell protected-nek lennie
     */

    protected function setFields(\Entities\Uzletkoto $obj)
    {
        $this->setEntityFieldsFromRequest($obj);
        $arsav = \mkw\store::getEm()->getRepository(Arsav::class)->find($this->params->getIntRequestParam('arsav'));
        if ($arsav) {
            $obj->setArsav($arsav);
        } else {
            $obj->removeArsav();
        }
        $fizmod = \mkw\store::getEm()->getRepository(Fizmod::class)->find($this->params->getIntRequestParam('partnerfizmod', 0));
        if ($fizmod) {
            $obj->setPartnerfizmod($fizmod);
        }
        $valutanem = \mkw\store::getEm()->getRepository(Valutanem::class)->find($this->params->getIntRequestParam('partnervalutanem', 0));
        if ($valutanem) {
            $obj->setPartnervalutanem($valutanem);
        }
        $szallmod = \mkw\store::getEm()->getRepository(Szallitasimod::class)->find($this->params->getIntRequestParam('partnerszallitasimod', 0));
        if ($szallmod) {
            $obj->setPartnerszallitasimod($szallmod);
        }
        $fouk = \mkw\store::getEm()->getRepository(Uzletkoto::class)->find($this->params->getIntRequestParam('fouzletkoto', 0));
        if ($fouk && $fouk->getFo()) {
            $obj->setFouzletkoto($fouk);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('uzletkotolista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $uk = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($uk, 'uzletkotolista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('uzletkotolista.tpl');
        $view->setVar('pagetitle', t('Üzletkötők'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    public function getSelectList($selid = null, $filter = [])
    {
        $rec = $this->getRepo()->getAll($filter, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Üzletkötő'));
        $view->setVar('oper', $oper);

        $partnerrepo = $this->getRepo(Partner::class);
        /** @var \Entities\Uzletkoto $uk */
        $uk = $this->getRepo()->findWithJoins($id);
        // loadVars utan nem abc sorrendben adja vissza

        $fizmod = new fizmodController();
        $view->setVar('partnerfizmodlist', $fizmod->getSelectList($uk?->getPartnerfizmod()?->getId()));
        $valutanem = new valutanemController();
        $view->setVar('partnervalutanemlist', $valutanem->getSelectList($uk?->getPartnervalutanem()?->getId()));
        $arsav = new arsavController();
        $view->setVar('arsavlist', $arsav->getSelectList($uk?->getArsav()?->getId()));

        $szallmod = new szallitasimodController();
        $view->setVar('partnerszallitasimodlist', $szallmod->getSelectList($uk?->getPartnerszallitasimod()?->getId()));
        $view->setVar('partnerszamlatipuslist', $partnerrepo->getSzamlatipusList($uk?->getPartnerszamlatipus()));
        $view->setVar('partnerbizonylatnyelvlist', \mkw\store::getLocaleSelectList($uk?->getPartnerbizonylatnyelv()));

        $fofilter = new \mkwhelpers\FilterDescriptor();
        $fofilter->addFilter('fo', '=', true);
        $view->setVar('fouzletkotolist', $this->getSelectList($uk?->getFouzletkoto()?->getId(), $fofilter));

        $view->setVar('uzletkoto', $this->loadVars($uk));
        $view->printTemplateResult();
    }

}
