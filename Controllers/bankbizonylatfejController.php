<?php
namespace Controllers;

use Entities\Bankbizonylattetel;
use mkw\store;

class bankbizonylatfejController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Bankbizonylatfej');
        $this->setKarbFormTplName('bankbizonylatfejkarbform.tpl');
        $this->setKarbTplName('bankbizonylatfejkarb.tpl');
        $this->setListBodyRowTplName('bankbizonylatfejlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t) {
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
        return $x;
    }

    /**
     * @param \Entities\Bankbizonylatfej $obj
     * @return \Entities\Bankbizonylatfej
     */
    protected function setFields($obj) {
        $obj->setErbizonylatszam($this->params->getStringRequestParam('erbizonylatszam'));
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setKelt($this->params->getStringRequestParam('kelt'));

        $ck = store::getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem'));
        if ($ck) {
            $obj->setValutanem($ck);
        }

        $ck = store::getEm()->getRepository('Entities\Bankszamla')->find($this->params->getIntRequestParam('bankszamla'));
        if ($ck) {
            $obj->setBankszamla($ck);
        }

        $obj->generateId(); // az üres kelt miatt került a végére

        $tetelids = $this->params->getArrayRequestParam('tetelid');
        $biztetelcontroller = new bizonylattetelController($this->params);
        foreach ($tetelids as $tetelid) {
            if (($this->params->getIntRequestParam('teteljogcim_' . $tetelid) > 0)) {
                $oper = $this->params->getStringRequestParam('teteloper_' . $tetelid);
                $jogcim = $this->getEm()->getRepository('Entities\Jogcim')->find($this->params->getIntRequestParam('teteljogcim_' . $tetelid));
                if ($jogcim) {
                    switch ($oper) {
                        case $this->addOperation:
                        case $this->inheritOperation:
                            $tetel = new Bankbizonylattetel();
                            $obj->addBizonylattetel($tetel);

                            $tetel->setJogcim($jogcim);

                            $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                            $tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
                            $tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));

                            $this->getEm()->persist($tetel);
                            break;
                        case $this->editOperation:
                            /** @var \Entities\Bankbizonylattetel $tetel */
                            $tetel = $this->getEm()->getRepository('Entities\Bankbizonylattetel')->find($tetelid);
                            if ($tetel) {
                                $tetel->setJogcim($jogcim);

                                $tetel->setAfa($this->params->getIntRequestParam('tetelafa_' . $tetelid));
                                $tetel->setNetto($this->params->getFloatRequestParam('tetelnetto_' . $tetelid));
                                $tetel->setBrutto($this->params->getFloatRequestParam('tetelbrutto_' . $tetelid));

                                $this->getEm()->persist($tetel);
                            }
                            break;
                    }
                }
                else {
                    \mkw\Store::writelog(print_r($this->params->asArray(), true), 'nincsjogcim.log');
                }
            }
        }

        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('bankbizonylatfejlista_tbody.tpl');

        $filter = array();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter['fields'][] = 'nev';
            $filter['values'][] = $this->params->getStringRequestParam('nevfilter');
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

        $view->setVar('pagetitle', t('Bankbizonylat'));
        $view->printTemplateResult();
    }

    public function viewlist() {
        $view = $this->createView('bankbizonylatfejlista.tpl');

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
        $record = $this->getRepo()->findWithJoins($id);
        $view->setVar('egyed', $this->loadVars($record));

        $bt = $this->getRepo('Entities\Bizonylattipus')->find('bank');
        $bt->setTemplateVars($view);

        $partner = new partnerController($this->params);
        $view->setVar('partnerlist', $partner->getSelectList(($record ? $record->getPartnerId() : 0)));

        $valutanem = new valutanemController($this->params);
        if (!$record || !$record->getValutanemId()) {
            $valutaid = store::getParameter(\mkw\consts::Valutanem, 0);
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