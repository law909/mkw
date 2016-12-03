<?php
namespace Controllers;

use Entities\Bankbizonylattetel;

class bankbizonylatfejController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Bankbizonylatfej');
        $this->setKarbFormTplName('bankbizonylatfejkarbform.tpl');
        $this->setKarbTplName('bankbizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bankbizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\Bankbizonylatfej();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['irany'] = $t->getIrany();
        $x['bizonylattipusid'] = $t->getBizonylattipusId();
        $x['rontott'] = $t->getRontott();
        $x['erbizonylatszam'] = $t->getErbizonylatszam();
        $x['megjegyzes'] = $t->getMegjegyzes();
        $x['keltstr'] = $t->getKeltStr();
        $x['netto'] = $t->getNetto();
        $x['afa'] = $t->getAfa();
        $x['brutto'] = $t->getBrutto();
        $x['valutanemnev'] = $t->getValutanemnev();
        $x['bankszamla'] = $t->getBankszamla();
        $x['bankszamlaszam'] = $t->getTulajbankszamlaszam();
        $x['swift'] = $t->getTulajswift();
        $x['iban'] = $t->getTulajiban();
        $x['partnernev'] = $t->getPartnernev();
        $x['partnerkeresztnev'] = $t->getPartnerkeresztnev();
        $x['partnervezeteknev'] = $t->getPartnervezeteknev();
        $x['partneradoszam'] = $t->getPartneradoszam();
        $x['partnereuadoszam'] = $t->getPartnereuadoszam();
        $x['partnerirszam'] = $t->getPartnerirszam();
        $x['partnervaros'] = $t->getPartnervaros();
        $x['partnerutca'] = $t->getPartnerutca();

        $x['nemrossz'] = !$t->getRontott();

        if ($forKarb) {
            $tetelCtrl = new bankbizonylattetelController($this->params);
            foreach ($t->getBizonylattetelek() as $ttetel) {
                $tetel[] = $tetelCtrl->loadVars($ttetel, true);
            }
            $x['tetelek'] = $tetel;

        }
        return $x;
    }

    /**
     * @param \Entities\Bankbizonylatfej $obj
     * @return \Entities\Bankbizonylatfej
     */
    protected function setFields($obj) {
        $type = $this->params->getStringRequestParam('type');

        $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setKelt($this->params->getStringRequestParam('kelt'));

        $valutanem = \mkw\store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
        if ($valutanem) {
            $obj->setValutanem($valutanem);
        }

        $ck = \mkw\store::getEm()->getRepository('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
        if ($ck) {
            $obj->setBankszamla($ck);
        }

        switch ($type) {
            case 'b':
                $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
                $obj->setBizonylattipus($bt);
                break;
            case 'p':
                break;
        }

        switch ($type) {
            case 'b':
                $tetelids = $this->params->getArrayRequestParam('tetelid');
                foreach ($tetelids as $tetelid) {
                    if (($this->params->getIntRequestParam('teteljogcim_' . $tetelid) > 0)) {
                        $oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
                        $irany = $this->params->getIntRequestParam('tetelirany_' . $tetelid);
                        $jogcim = $this->getEm()->getRepository('Entities\Jogcim')->find($this->params->getIntRequestParam('teteljogcim_' . $tetelid));
                        $partner = $this->getEm()->getRepository('Entities\Partner')->find($this->params->getIntRequestParam('tetelpartner_' . $tetelid));
                        if ($jogcim && $partner) {
                            switch ($oper) {
                                case $this->addOperation:
                                case $this->inheritOperation:
                                    $tetel = new Bankbizonylattetel();
                                    $obj->addBizonylattetel($tetel);

                                    $tetel->setJogcim($jogcim);
                                    if ($irany < 0) {
                                        $tetel->setIrany(-1);
                                    }
                                    else {
                                        $tetel->setIrany(1);
                                    }

                                    $tetel->setValutanem($valutanem);
                                    $tetel->setPartner($partner);
                                    $tetel->setDatum($this->params->getStringRequestParam('teteldatum_' . $tetelid));
                                    $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat_' . $tetelid));
                                    $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum_' . $tetelid));
                                    $tetel->setErbizonylatszam($this->params->getStringRequestParam('tetelerbizonylatszam_' . $tetelid));

                                    $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg_' . $tetelid));

                                    $this->getEm()->persist($tetel);
                                    break;
                                case $this->editOperation:
                                    /** @var \Entities\Bankbizonylattetel $tetel */
                                    $tetel = $this->getEm()->getRepository('Entities\Bankbizonylattetel')->find($tetelid);
                                    if ($tetel) {
                                        $tetel->setJogcim($jogcim);
                                        if ($irany < 0) {
                                            $tetel->setIrany(-1);
                                        }
                                        else {
                                            $tetel->setIrany(1);
                                        }
                                        $tetel->setValutanem($valutanem);
                                        $tetel->setPartner($partner);
                                        $tetel->setDatum($this->params->getStringRequestParam('teteldatum_' . $tetelid));
                                        $tetel->setHivatkozottbizonylat($this->params->getStringRequestParam('tetelhivatkozottbizonylat_' . $tetelid));
                                        $tetel->setHivatkozottdatum($this->params->getStringRequestParam('tetelhivatkozottdatum_' . $tetelid));
                                        $tetel->setErbizonylatszam($this->params->getStringRequestParam('tetelerbizonylatszam_' . $tetelid));

                                        $tetel->setBrutto($this->params->getFloatRequestParam('tetelosszeg_' . $tetelid));

                                        $this->getEm()->persist($tetel);
                                    }
                                    break;
                            }
                        }
                        else {
                            \mkw\store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                        }
                    }
                }
                break;
        }

        return $obj;
    }

    protected function setVars($view) {
        $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
        if ($bt) {
            $bt->setTemplateVars($view);
        }

        $vc = new valutanemController($this->params);
        $view->setVar('valutanemlist', $vc->getSelectList());

        $bc = new bankszamlaController($this->params);
        $view->setVar('bankszamlalist', $bc->getSelectList());
    }

    public function getlistbody() {
        $view = $this->createView('bankbizonylatfejlista_tbody.tpl');

        $this->setVars($view);

        $filter = new \mkwhelpers\FilterDescriptor();

        if (!is_null($this->params->getRequestParam('idfilter', NULL))) {
            $filter->addFilter('id', 'LIKE', '%' . $this->params->getStringRequestParam('idfilter'));
        }

        $v = $this->getRepo('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanemfilter'));
        if ($v) {
            $filter->addFilter('valutanem', '=', $v);
        }

        $b = $this->getRepo('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamlafilter'));
        if ($b) {
            $filter->addFilter('bankszamla', '=', $b);
        }

        $tol = $this->params->getStringRequestParam('datumtolfilter');
        $ig = $this->params->getStringRequestParam('datumigfilter');
        if ($tol || $ig) {
            if ($tol) {
                $filter->addFilter('kelt', '>=', $tol);
            }
            if ($ig) {
                $filter->addFilter('kelt', '<=', $ig);
            }
        }
        $f = $this->params->getStringRequestParam('erbizonylatszamfilter');
        if ($f) {
            $filter->addFilter('erbizonylatszam', 'LIKE', '%' . $f . '%');
        }
        $f = $this->params->getIntRequestParam('bizonylatrontottfilter');
        switch ($f) {
            case 1:
                $filter->addFilter('rontott', '=', false);
                break;
            case 2:
                $filter->addFilter('rontott', '=', true);
                break;
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect() {
        $view = $this->createView('bankbizonylatfejlista.tpl');

        $this->setVars($view);

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bankbizonylatfejlista.tpl');

        $this->setVars($view);

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->setVar('oper', $oper);
        $view->setVar('formaction', '/admin/bankbizonylatfej/save');
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record, true));

        $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
        $bt->setTemplateVars($view);

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        $valutanem = new valutanemController($this->params);
        if (!$record || !$record->getValutanemId()) {
            $valutaid = \mkw\store::getParameter(\mkw\consts::Valutanem, 0);
        }
        else {
            $valutaid = $record->getValutanemId();
        }
        $view->setVar('valutanemlist', $valutanem->getSelectList($valutaid));

        $bankszla = new bankszamlaController($this->params);
        $bankszlaid = false;
        if ($record && $record->getBankszamlaId()) {
            $bankszlaid = $record->getBankszamlaId();
        }
        else {
            $valutanem = $this->getRepo('Entities\Valutanem')->find($valutaid);
            if ($valutanem && $valutanem->getBankszamlaId()) {
                $bankszlaid = $valutanem->getBankszamlaId();
            }
        }
        $view->setVar('bankszamlalist', $bankszla->getSelectList($bankszlaid));

        return $view->getTemplateResult();
    }

}