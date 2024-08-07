<?php

namespace Controllers;

use Entities\MPTFolyoszamla;
use Entities\Partner;
use Entities\Rendezveny;

class mptfolyoszamlaController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(MPTFolyoszamla::class);
        $this->setKarbFormTplName('mptfolyoszamlakarbform.tpl');
        $this->setKarbTplName('mptfolyoszamlakarb.tpl');
        $this->setListBodyRowTplName('mptfolyoszamlalista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\MPTFolyoszamla();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['tipus'] = $t->getTipus();
        $x['tipusnev'] = $t->getTipusnev();
        $x['irany'] = $t->getIrany();
        $x['bizonylatszam'] = $t->getBizonylatszam();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['osszeg'] = $t->getOsszeg();
        $x['partner'] = $t->getPartnerId();
        $x['partnernev'] = $t->getPartnernev();
        $x['datum'] = $t->getDatumStr();
        $x['vonatkozoev'] = $t->getVonatkozoev();

        if ($forKarb) {
        }
        return $x;
    }

    /**
     * @param \Entities\Helyszin $obj
     * @param $oper
     *
     * @return mixed
     */
    protected function setFields($obj, $oper)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setEmailsablon($this->params->getOriginalStringRequestParam('emailsablon'));
        $obj->setAr($this->params->getNumRequestParam('ar'));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('mptfolyoszamlalista_tbody.tpl');

        $filterarr = new \mkwhelpers\FilterDescriptor();

        $this->initPager($this->getRepo()->getCount($filterarr));

        $egyedek = $this->getRepo()->getWithJoins(
            $filterarr,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        $res = [];
        /** @var \Entities\Helyszin $sor */
        foreach ($rec as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function viewselect()
    {
        $view = $this->createView('helyszinlista.tpl');

        $view->setVar('pagetitle', t('Helyszínek'));
        $view->printTemplateResult(false);
    }

    public function viewlist()
    {
        $view = $this->createView('helyszinlista.tpl');

        $view->setVar('pagetitle', t('Helyszínek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult(false);
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Helyszin'));
        $view->setVar('formaction', '/admin/helyszin/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getEmptyEloirasRow()
    {
        $view = $this->createView('mptfolyoszamlaeloiraskarb.tpl');
        $view->setVar('ar', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function saveeloiras()
    {
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partnerid'));
        if ($partner) {
            $vonatkozoev = $this->params->getIntRequestParam('vonatkozoev');
            $osszeg = $this->params->getIntRequestParam('osszeg');
            $eloiras = new \Entities\MPTFolyoszamla();
            $eloiras->setPartner($partner);
            $eloiras->setVonatkozoev($vonatkozoev);
            $eloiras->setOsszeg($osszeg);
            $eloiras->setDatum();
            $eloiras->setTipus('E');
            $eloiras->setIrany(-1);
            $eloiras->setBizonylatszam('kézi');

            $this->getEm()->persist($eloiras);
            $this->getEm()->flush();

            $partnerctrl = new partnerController($this->params);
            $partnerarr = $partnerctrl->loadVars($partner);
            $view = $this->createView('mptfolyoszamlatabla.tpl');
            $view->setVar('partner', $partnerarr);
            $view->printTemplateResult();
        }
    }

    public function del()
    {
        $id = $this->params->getIntRequestParam('id');
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partnerid'));
        if ($partner && $id) {
            $this->getEm()->remove($this->getRepo()->find($id));
            $this->getEm()->flush();

            $partnerctrl = new partnerController($this->params);
            $partnerarr = $partnerctrl->loadVars($partner);
            $view = $this->createView('mptfolyoszamlatabla.tpl');
            $view->setVar('partner', $partnerarr);
            $view->printTemplateResult();
        }
    }

    public function getEmptyBefizetesRow()
    {
        $view = $this->createView('mptfolyoszamlabefizeteskarb.tpl');
        $view->setVar('ar', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function savebefizetes()
    {
        $partner = $this->getRepo(Partner::class)->find($this->params->getIntRequestParam('partnerid'));
        if ($partner) {
            $vonatkozoev = $this->params->getIntRequestParam('vonatkozoev');
            $osszeg = $this->params->getIntRequestParam('osszeg');
            $datum = $this->params->getStringRequestParam('datum');
            $bizszam = $this->params->getStringRequestParam('bizonylatszam');
            $befizetes = new \Entities\MPTFolyoszamla();
            $befizetes->setPartner($partner);
            $befizetes->setVonatkozoev($vonatkozoev);
            $befizetes->setOsszeg($osszeg);
            $befizetes->setDatum($datum);
            $befizetes->setTipus('B');
            $befizetes->setIrany(1);
            $befizetes->setBizonylatszam($bizszam);

            $this->getEm()->persist($befizetes);
            $this->getEm()->flush();

            $partnerctrl = new partnerController($this->params);
            $partnerarr = $partnerctrl->loadVars($partner);
            $view = $this->createView('mptfolyoszamlatabla.tpl');
            $view->setVar('partner', $partnerarr);
            $view->printTemplateResult();
        }
    }
}