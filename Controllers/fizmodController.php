<?php

namespace Controllers;

use Entities\Fizmod;

class fizmodController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Fizmod');
        $this->setKarbFormTplName('fizetesimodkarbform.tpl');
        $this->setKarbTplName('fizetesimodkarb.tpl');
        $this->setListBodyRowTplName('fizetesimodlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $letezik = true;
        $translationsCtrl = new fizmodtranslationController($this->params);
        $translations = [];
        $x = [];
        if (!$t) {
            $letezik = false;
            $t = new \Entities\Fizmod();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['tipus'] = $t->getTipus();
        $x['navtipus'] = $t->getNavtipus();
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
        $x['osztotthaladek4'] = $t->getOsztotthaladek4();
        $x['osztottszazalek4'] = $t->getOsztottszazalek4();
        $x['osztotthaladek5'] = $t->getOsztotthaladek5();
        $x['osztottszazalek5'] = $t->getOsztottszazalek5();
        $x['rugalmas'] = $t->getRugalmas();
        $x['nincspenzmozgas'] = $t->getNincspenzmozgas();
        $x['emagid'] = $t->getEmagid();

        if ($forKarb) {
            if ($letezik) {
                $fhc = new fizmodhatarController($this->params);
                $h = $this->getRepo('Entities\FizmodHatar')->getByFizmod($t);
                $hatararr = [];
                foreach ($h as $hat) {
                    $hatararr[] = $fhc->loadVars($hat, $forKarb);
                }
                $x['hatarok'] = $hatararr;
            }
            if (\mkw\store::isMultilang()) {
                foreach ($t->getTranslations() as $tr) {
                    $translations[] = $translationsCtrl->loadVars($tr, true);
                }
                $x['translations'] = $translations;
            }
        }
        return $x;
    }

    /**
     * @param \Entities\Fizmod $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setTipus($this->params->getStringRequestParam('tipus'));
        $obj->setNavtipus($this->params->getStringRequestParam('navtipus'));
        $obj->setHaladek($this->params->getIntRequestParam('haladek'));
        $obj->setWebes($this->params->getBoolRequestParam('webes'));
        $obj->setLeiras($this->params->getOriginalStringRequestParam('leiras'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setOsztotthaladek1($this->params->getIntRequestParam('osztotthaladek1'));
        $obj->setOsztottszazalek1($this->params->getNumRequestParam('osztottszazalek1'));
        $obj->setOsztotthaladek2($this->params->getIntRequestParam('osztotthaladek2'));
        $obj->setOsztottszazalek2($this->params->getNumRequestParam('osztottszazalek2'));
        $obj->setOsztotthaladek3($this->params->getIntRequestParam('osztotthaladek3'));
        $obj->setOsztottszazalek3($this->params->getNumRequestParam('osztottszazalek3'));
        $obj->setOsztotthaladek4($this->params->getIntRequestParam('osztotthaladek4'));
        $obj->setOsztottszazalek4($this->params->getNumRequestParam('osztottszazalek4'));
        $obj->setOsztotthaladek5($this->params->getIntRequestParam('osztotthaladek5'));
        $obj->setOsztottszazalek5($this->params->getNumRequestParam('osztottszazalek5'));
        $obj->setRugalmas($this->params->getBoolRequestParam('rugalmas'));
        $obj->setNincspenzmozgas($this->params->getBoolRequestParam('nincspenzmozgas'));
        $obj->setEmagid($this->params->getIntRequestParam('emagid'));
        $hatarids = $this->params->getArrayRequestParam('hatarid');
        foreach ($hatarids as $hatarid) {
            $oper = $this->params->getStringRequestParam('hataroper_' . $hatarid);
            $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find($this->params->getIntRequestParam('hatarvalutanem_' . $hatarid));
            if (!$valutanem) {
                $valutanem = $this->getEm()->getRepository('Entities\Valutanem')->find(\mkw\store::getParameter(\mkw\consts::Valutanem));
            }
            if ($oper == 'add') {
                $hatar = new \Entities\FizmodHatar();
                $hatar->setFizmod($obj);
                $hatar->setHatarertek($this->params->getNumRequestParam('hatarertek_' . $hatarid));
                if ($valutanem) {
                    $hatar->setValutanem($valutanem);
                }
                $this->getEm()->persist($hatar);
            } elseif ($oper == 'edit') {
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
        if (\mkw\store::isMultilang()) {
            $_tf = \Entities\Fizmod::getTranslatedFields();
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
                    $translation = new \Entities\FizmodTranslation(
                        $this->params->getStringRequestParam('translationlocale_' . $translationid),
                        $mezo,
                        $mezoertek
                    );
                    $obj->addTranslation($translation);
                    $this->getEm()->persist($translation);
                } elseif ($oper === 'edit') {
                    $translation = $this->getEm()->getRepository('Entities\FizmodTranslation')->find($translationid);
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

    public function getlistbody()
    {
        $view = $this->createView('fizetesimodlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('fizetesimodlista.tpl');

        $view->setVar('pagetitle', t('Fizetési módok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Fizetési mód'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminfizetesimodsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $szallmod = null, $exc = null)
    {
        $szepfm = \mkw\store::getParameter(\mkw\consts::SZEPFizmod);
        $sportfm = \mkw\store::getParameter(\mkw\consts::SportkartyaFizmod);
        $aycmfm = \mkw\store::getParameter(\mkw\consts::AYCMFizmod);

        if (\mkw\store::isAdminMode()) {
            $rec = $this->getRepo()->getAllBySzallitasimod($szallmod, $exc);
        } else {
            $rec = $this->getRepo()->getAllWebesBySzallitasimod($szallmod, $exc);
        }

        $res = [];
        // mkwnál ki kell választani az elsőt
        $vanvalasztott = \mkw\store::getTheme() !== 'mkwcansas';
        /** @var Fizmod $sor */
        foreach ($rec as $sor) {
            $r = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'fizhatido' => $sor->getHaladek(),
                'leiras' => $sor->getLeiras(),
                'bank' => ($sor->getTipus() == 'B' ? '1' : '0'),
                'szepkartya' => $sor->getId() == $szepfm,
                'sportkartya' => $sor->getId() == $sportfm,
                'aycm' => $sor->getId() == $aycmfm,
                'nincspenzmozgas' => $sor->getNincspenzmozgas(),
                'bankkartyas' => $sor->getNavtipus() == 'CARD'
            ];
            if ($selid) {
                $r['selected'] = $sor->getId() == $selid;
            } else {
                if (!$vanvalasztott) {
                    $r['selected'] = true;
                    $vanvalasztott = true;
                } else {
                    $r['selected'] = false;
                }
            }
            $res[] = $r;
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

}
