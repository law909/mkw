<?php

namespace Controllers;

use mkw\store;

class fizmodController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Fizmod');
        $this->setKarbFormTplName('fizetesimodkarbform.tpl');
        $this->setKarbTplName('fizetesimodkarb.tpl');
        $this->setListBodyRowTplName('fizetesimodlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false) {
        $letezik = true;
        $x = array();
        if (!$t) {
            $letezik = false;
            $t = new \Entities\Fizmod();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['tipus'] = $t->getTipus();
        $x['haladek'] = $t->getHaladek();
        $x['webes'] = $t->getWebes();
        $x['leiras'] = $t->getLeiras();
        $x['sorrend'] = $t->getSorrend();
        $x['osztotthaladek1'] = $t->getOsztotthaladek1();
        $x['osztottszazalek1'] = $t->getOsztottszazalek1();
        $x['osztotthaladek2'] = $t->getOsztotthaladek2();
        $x['osztottszazalek2'] = $t->getOsztottszazalek2();
        $x['osztotthaladek3'] = $t->getOsztotthaladek3();
        $x['osztottszazalek3'] = $t->getOsztottszazalek3();
        $x['rugalmas'] = $t->getRugalmas();

        if ($forKarb) {
            if ($letezik) {
                $fhc = new fizmodhatarController($this->params);
                $h = $this->getRepo('Entities\FizmodHatar')->getByFizmod($t);
                $hatararr = array();
                foreach ($h as $hat) {
                    $hatararr[] = $fhc->loadVars($hat, $forKarb);
                }
                $x['hatarok'] = $hatararr;
            }
        }
        return $x;
    }

    protected function setFields($obj) {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setTipus($this->params->getStringRequestParam('tipus'));
        $obj->setHaladek($this->params->getIntRequestParam('haladek'));
        $obj->setWebes($this->params->getBoolRequestParam('webes'));
        $obj->setLeiras($this->params->getStringRequestParam('leiras'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setOsztotthaladek1($this->params->getIntRequestParam('osztotthaladek1'));
        $obj->setOsztottszazalek1($this->params->getNumRequestParam('osztottszazalek1'));
        $obj->setOsztotthaladek2($this->params->getIntRequestParam('osztotthaladek2'));
        $obj->setOsztottszazalek2($this->params->getNumRequestParam('osztottszazalek2'));
        $obj->setOsztotthaladek3($this->params->getIntRequestParam('osztotthaladek3'));
        $obj->setOsztottszazalek3($this->params->getNumRequestParam('osztottszazalek3'));
        $obj->setRugalmas($this->params->getBoolRequestParam('rugalmas'));
        $hatarids = $this->params->getArrayRequestParam('hatarid');
        foreach ($hatarids as $hatarid) {
            $oper = $this->params->getStringRequestParam('hataroper_' . $hatarid);
            $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('hatarvalutanem_' . $hatarid));
            if (!$valutanem) {
                $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find(store::getParameter(\mkw\consts::Valutanem));
            }
            if ($oper == 'add') {
                $hatar = new \Entities\FizmodHatar();
                $hatar->setFizmod($obj);
                $hatar->setHatarertek($this->params->getNumRequestParam('hatarertek_' . $hatarid));
                if ($valutanem) {
                    $hatar->setValutanem($valutanem);
                }
                $this->getEm()->persist($hatar);
            }
            elseif ($oper == 'edit') {
                $hatar = $this->getEm()->getRepository('Entities\FizmodHatar')->find($hatarid);
                if ($hatar) {
                    $hatar->setHatarertek($this->params->getNumRequestParam('hatarertek_' . $hatarid));
                    if ($valutanem) {
                        $hatar->setValutanem($valutanem);
                    }
                    $this->getEm()->persist($hatar);
                }
            }
        }
        return $obj;
    }

    public function getlistbody() {
        $view = $this->createView('fizetesimodlista_tbody.tpl');

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
        $view = $this->createView('fizetesimodlista.tpl');

        $view->setVar('pagetitle', t('Fizetési módok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname) {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Fizetési mód'));
        $view->setVar('formaction', \mkw\Store::getRouter()->generate('adminfizetesimodsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $szallmod = null, $exc = null) {
        $rec = $this->getRepo()->getAllWebesBySzallitasimod($szallmod, $exc);
        $res = array();
        // mkwnál ki kell választani az elsőt
        $vanvalasztott = \mkw\Store::getTheme() !== 'mkwcansas';
        foreach ($rec as $sor) {
            $r = array(
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'fizhatido' => $sor->getHaladek(),
                'leiras' => $sor->getLeiras(),
                'bank' => ($sor->getTipus() == 'B' ? '1' : '0')
            );
            if ($selid) {
                $r['selected'] = $sor->getId() == $selid;
            }
            else {
                if (!$vanvalasztott) {
                    $r['selected'] = true;
                    $vanvalasztott = true;
                }
                else {
                    $r['selected'] = false;
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
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

}
