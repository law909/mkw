<?php

namespace Controllers;

class szallitasimodController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Szallitasimod');
        $this->setKarbFormTplName('szallitasimodkarbform.tpl');
        $this->setKarbTplName('szallitasimodkarb.tpl');
        $this->setListBodyRowTplName('szallitasimodlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $letezik = true;
        $translationsCtrl = new szallitasimodtranslationController($this->params);
        $translations = array();
        $x = array();
        if (!$t) {
            $letezik = false;
            $t = new \Entities\Szallitasimod();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['webes'] = $t->getWebes();
        $x['webes2'] = $t->getWebes2();
        $x['webes3'] = $t->getWebes3();
        $x['leiras'] = $t->getLeiras();
        $x['fizmodok'] = $t->getFizmodok();
        $x['sorrend'] = $t->getSorrend();
        $x['vanszallitasiktg'] = $t->getVanszallitasiktg();
        $x['terminaltipus'] = $t->getTerminaltipus();

        if ($forKarb) {
            if ($letezik) {
                $fhc = new szallitasimodhatarController($this->params);
                $h = $this->getRepo('Entities\SzallitasimodHatar')->getBySzallitasimod($t);
                $hatararr = array();
                foreach ($h as $hat) {
                    $hatararr[] = $fhc->loadVars($hat, $forKarb);
                }
                $x['hatarok'] = $hatararr;
            }
            if (\mkw\store::isMultilang()) {
                foreach($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr, true);
                }
                $x['translations'] = $translations;
            }
        }
        return $x;
    }

    /**
     * @param \Entities\Szallitasimod $obj
     * @return mixed
     */
    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setWebes($this->params->getBoolRequestParam('webes'));
        $obj->setWebes2($this->params->getBoolRequestParam('webes2'));
        $obj->setWebes3($this->params->getBoolRequestParam('webes3'));
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setFizmodok($this->params->getStringRequestParam('fizmodok'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setVanszallitasiktg($this->params->getBoolRequestParam('vanszallitasiktg'));
        $obj->setTerminaltipus($this->params->getStringRequestParam('terminaltipus'));
        $hatarids = $this->params->getArrayRequestParam('hatarid');
        foreach ($hatarids as $hatarid) {
            $oper = $this->params->getStringRequestParam('hataroper_' . $hatarid);
            $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('hatarvalutanem_' . $hatarid));
            if (!$valutanem) {
                $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
            }
            if ($oper === 'add') {
                $hatar = new \Entities\SzallitasimodHatar();
                $hatar->setSzallitasimod($obj);
                $hatar->setHatarertek($this->params->getNumRequestParam('hatarertek_' . $hatarid));
                $hatar->setOsszeg($this->params->getNumRequestParam('osszeg_' . $hatarid));
                if ($valutanem) {
                    $hatar->setValutanem($valutanem);
                }
                $this->getEm()->persist($hatar);
            }
            elseif ($oper === 'edit') {
                $hatar = $this->getEm()->getRepository('Entities\SzallitasimodHatar')->find($hatarid);
                if ($hatar) {
                    $hatar->setHatarertek($this->params->getNumRequestParam('hatarertek_' . $hatarid));
                    $hatar->setOsszeg($this->params->getNumRequestParam('osszeg_' . $hatarid));
                    if ($valutanem) {
                        $hatar->setValutanem($valutanem);
                    }
                    $this->getEm()->persist($hatar);
                }
            }
        }
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\Szallitasimod::getTranslatedFields();
            $translationids = $this->params->getArrayRequestParam('translationid');
            foreach ($translationids as $translationid) {
                $oper = $this->params->getStringRequestParam('translationoper_' . $translationid);
                $mezo = $this->params->getStringRequestParam('translationfield_' . $translationid);
                $mezotype = $_tf[$mezo]['type'];
                switch ($mezotype) {
                    case 1:
                    case 3:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                    case 2:
                        $mezoertek = $this->params->getOriginalStringRequestParam('translationcontent_' . $translationid);
                        break;
                    default:
                        $mezoertek = $this->params->getStringRequestParam('translationcontent_' . $translationid);
                        break;
                }
                if ($oper === 'add') {
                    $translation = new \Entities\SzallitasimodTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
                    $obj->addTranslation($translation);
                    $this->getEm()->persist($translation);
                }
                elseif ($oper === 'edit') {
                    $translation = $this->getEm()->getRepository('Entities\SzallitasimodTranslation')->find($translationid);
                    if ($translation) {
                        $translation->setLocale($this->params->getStringRequestParam('translationlocale_' . $translationid));
                        $translation->setField($mezo);
                        $translation->setContent($mezoertek);
                        $this->getEm()->persist($translation);
                    }
                }
            }
        }

        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('szallitasimodlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', NULL))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1));

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage());

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist() {
        $view = $this->createView('szallitasimodlista.tpl');

        $view->setVar('pagetitle', t('Szállítási módok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Szállítási mód'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminszallitasimodsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $mind = false) {
        $foxpostid = \mkw\store::getParameter(\mkw\consts::FoxpostSzallitasiMod);
        if ($mind) {
            $rec = $this->getRepo()->getAll(array(),array('sorrend'=>'ASC','nev'=>'ASC'));
        }
        else {
            $rec = $this->getRepo()->getAllWebes();
        }
        $res = array();
        // mkwnál ki kell választani az elsőt
        $vanvalasztott = true; // \mkw\store::getTheme() !== 'mkwcansas';
        foreach ($rec as $sor) {
            $r = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'leiras' => $sor->getLeiras(),
                'foxpost' => ($sor->getId() == $foxpostid),
                'terminaltipus' => $sor->getTerminaltipus()
            );
            if ($selid) {
                $r['selected'] = $sor->getId() == $selid;
            }
            else {
                if (!$mind) {
                    if (!$vanvalasztott) {
                        $r['selected'] = true;
                        $vanvalasztott = true;
                    }
                    else {
                        $r['selected'] = false;
                    }
                }
            }
            $res[] = $r;
        }
        return $res;
    }

    public function htmllist() {
        $rec = $this->getRepo()->getAll(array(), array('nev' => 'asc'));
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret.='<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret.='</select>';
        echo $ret;
    }

}
