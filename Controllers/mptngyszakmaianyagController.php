<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\MPTNGYSzakmaianyag;
use Entities\MPTNGYSzakmaianyagtipus;
use Entities\Partner;
use mkwhelpers\FilterDescriptor;

class mptngyszakmaianyagController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(MPTNGYSzakmaianyag::class);
        $this->setKarbFormTplName('mptngyszakmaianyagkarbform.tpl');
        $this->setKarbTplName('mptngyszakmaianyagkarb.tpl');
        $this->setListBodyRowTplName('mptngyszakmaianyaglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct($params);
    }

    protected function loadVars($t, $forKarb = false)
    {
        $x = array();
        if (!$t) {
            $t = new \Entities\MPTNGYSzakmaianyag();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['cim'] = $t->getCim();
        $x['lastmodstr'] = $t->getLastmodStr();
        $x['createdstr'] = $t->getCreatedStr();
        $x['tartalom'] = $t->getTartalom();
        $x['tulajdonos'] = $t->getTulajdonosId();
        $x['tulajdonosnev'] = $t->getTulajdonos()?->getNev();
        $x['szerzo1'] = $t->getSzerzo1Id();
        $x['szerzo1nev'] = $t->getSzerzo1Nev();
        $x['szerzo1email'] = $t->getSzerzo1email();
        $x['szerzo2'] = $t->getSzerzo2Id();
        $x['szerzo2nev'] = $t->getSzerzo2Nev();
        $x['szerzo2email'] = $t->getSzerzo2email();
        $x['szerzo3'] = $t->getSzerzo3Id();
        $x['szerzo3nev'] = $t->getSzerzo3Nev();
        $x['szerzo3email'] = $t->getSzerzo3email();
        $x['szerzo4'] = $t->getSzerzo4Id();
        $x['szerzo4nev'] = $t->getSzerzo4Nev();
        $x['szerzo4email'] = $t->getSzerzo4email();
        $x['szerzo5'] = $t->getSzerzo5Id();
        $x['szerzo5nev'] = $t->getSzerzo5Nev();
        $x['szerzo5email'] = $t->getSzerzo5email();
        $x['kezdodatum'] = $t->getKezdodatumStr();
        $x['kezdoido'] = $t->getKezdoido();
        $x['tipus'] = $t->getTipusId();
        $x['tipusnev'] = $t->getTipus()?->getNev();
        $x['eloadas1'] = $t->getEloadas1Id();
        $x['eloadas1cim'] = $t->getEloadas1Cim();
        $x['eloadas2'] = $t->getEloadas2Id();
        $x['eloadas2cim'] = $t->getEloadas2Cim();
        $x['eloadas3'] = $t->getEloadas3Id();
        $x['eloadas3cim'] = $t->getEloadas3Cim();
        $x['eloadas4'] = $t->getEloadas4Id();
        $x['eloadas4cim'] = $t->getEloadas4Cim();
        $x['eloadas5'] = $t->getEloadas5Id();
        $x['eloadas5cim'] = $t->getEloadas5Cim();
        $x['kulcsszo1'] = $t->getKulcsszo1();
        $x['kulcsszo2'] = $t->getKulcsszo2();
        $x['kulcsszo3'] = $t->getKulcsszo3();
        $x['kulcsszo4'] = $t->getKulcsszo4();
        $x['kulcsszo5'] = $t->getKulcsszo5();
        $x['biralatkesz'] = $t->isBiralatkesz();
        $x['konferencianszerepelhet'] = $t->isKonferencianszerepelhet();
        $x['biralo1'] = $t->getBiralo1Id();
        $x['biralo1nev'] = $t->getBiralo1Nev();
        $x['biralo2'] = $t->getBiralo2Id();
        $x['biralo2nev'] = $t->getBiralo2Nev();
        $x['biralo3'] = $t->getBiralo3Id();
        $x['biralo3nev'] = $t->getBiralo3Nev();
        $x['allszerzoregistered'] = $t->isAllSzerzoRegistered();
        $x['szimpozium'] = ($t->getTipusId() == \mkw\store::getParameter(\mkw\consts::MPTNGYSzimpoziumTipus));
        if ($forKarb) {
        }

        return $x;
    }

    /**
     * @param \Entities\MPTNGYSzakmaianyag $obj
     * @return \Entities\MPTNGYSzakmaianyag
     */
    public function setFields($obj)
    {
        $obj->setCim($this->params->getStringRequestParam('cim'));
        $obj->setTartalom($this->params->getStringRequestParam('tartalom'));
        $obj->setBiralatkesz($this->params->getBoolRequestParam('biralatkesz'));
        $obj->setKezdodatum($this->params->getStringRequestParam('kezdodatum'));
        $obj->setKezdoido($this->params->getStringRequestParam('kezdoido'));
        $obj->setKonferencianszerepelhet($this->params->getBoolRequestParam('konferencianszerepelhet'));
        $obj->setKulcsszo1($this->params->getStringRequestParam('kulcsszo1'));
        $obj->setKulcsszo2($this->params->getStringRequestParam('kulcsszo2'));
        $obj->setKulcsszo3($this->params->getStringRequestParam('kulcsszo3'));
        $obj->setKulcsszo4($this->params->getStringRequestParam('kulcsszo4'));
        $obj->setKulcsszo5($this->params->getStringRequestParam('kulcsszo5'));
        $obj->setSzerzo1email($this->params->getStringRequestParam('szerzo1email'));
        $obj->setSzerzo2email($this->params->getStringRequestParam('szerzo2email'));
        $obj->setSzerzo3email($this->params->getStringRequestParam('szerzo3email'));
        $obj->setSzerzo4email($this->params->getStringRequestParam('szerzo4email'));
        $obj->setSzerzo5email($this->params->getStringRequestParam('szerzo5email'));

        $tipus = \mkw\store::getEm()->getRepository(MPTNGYSzakmaianyagtipus::class)->find($this->params->getIntRequestParam('tipus'));
        if ($tipus) {
            $obj->setTipus($tipus);
        } else {
            $obj->removeTipus();
        }

        $tulaj = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('tulajdonos'));
        if ($tulaj) {
            $obj->setTulajdonos($tulaj);
        } else {
            $obj->removeTulajdonos();
        }

        $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo1'));
        if ($szerzo) {
            $obj->setSzerzo1($szerzo);
        } else {
            $obj->removeSzerzo1();
        }
        $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo2'));
        if ($szerzo) {
            $obj->setSzerzo2($szerzo);
        } else {
            $obj->removeSzerzo2();
        }
        $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo3'));
        if ($szerzo) {
            $obj->setSzerzo3($szerzo);
        } else {
            $obj->removeSzerzo3();
        }
        $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo4'));
        if ($szerzo) {
            $obj->setSzerzo4($szerzo);
        } else {
            $obj->removeSzerzo4();
        }
        $szerzo = \mkw\store::getEm()->getRepository(Partner::class)->find($this->params->getIntRequestParam('szerzo5'));
        if ($szerzo) {
            $obj->setSzerzo5($szerzo);
        } else {
            $obj->removeSzerzo5();
        }

        $biralo = \mkw\store::getEm()->getRepository(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('biralo1'));
        if ($biralo) {
            $obj->setBiralo1($biralo);
        } else {
            $obj->removeBiralo1();
        }
        $biralo = \mkw\store::getEm()->getRepository(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('biralo2'));
        if ($biralo) {
            $obj->setBiralo2($biralo);
        } else {
            $obj->removeBiralo2();
        }
        $biralo = \mkw\store::getEm()->getRepository(\Entities\Dolgozo::class)->find($this->params->getIntRequestParam('biralo3'));
        if ($biralo) {
            $obj->setBiralo3($biralo);
        } else {
            $obj->removeBiralo3();
        }

        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas1'));
        if ($eloadas) {
            $obj->setEloadas1($eloadas);
        } else {
            $obj->removeEloadas1();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas2'));
        if ($eloadas) {
            $obj->setEloadas2($eloadas);
        } else {
            $obj->removeEloadas2();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas3'));
        if ($eloadas) {
            $obj->setEloadas3($eloadas);
        } else {
            $obj->removeEloadas3();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas4'));
        if ($eloadas) {
            $obj->setEloadas4($eloadas);
        } else {
            $obj->removeEloadas4();
        }
        $eloadas = \mkw\store::getEm()->getRepository(\Entities\MPTNGYSzakmaianyag::class)->find($this->params->getIntRequestParam('eloadas5'));
        if ($eloadas) {
            $obj->setEloadas5($eloadas);
        } else {
            $obj->removeEloadas5();
        }

        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('mptngyszakmaianyaglista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('cimfilter', null))) {
            $fv = $this->params->getStringRequestParam('cimfilter');
            $filter->addFilter('cim', 'LIKE', '%' . $fv . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

        $egyedek = $this->getRepo()->getWithJoins(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewlist()
    {
        $view = $this->createView('mptngyszakmaianyaglista.tpl');

        $view->setVar('pagetitle', t('Szakmai anyagok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    public function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Szakmai anyag'));
        $view->setVar('oper', $oper);

        /** @var MPTNGYSzakmaianyag $anyag */
        $anyag = $this->getRepo()->findWithJoins($id);
        // loadVars utan nem abc sorrendben adja vissza
        $tc = new mptngyszakmaianyagtipusController($this->params);
        $view->setVar('tipuslist', $tc->getSelectList($anyag?->getTipusId()));

        $pc = new partnerController($this->params);
        $view->setVar('tulajdonoslist', $pc->getSelectList($anyag?->getTulajdonosId()));
        $view->setVar('szerzo1list', $pc->getSelectList($anyag?->getSzerzo1Id()));
        $view->setVar('szerzo2list', $pc->getSelectList($anyag?->getSzerzo2Id()));
        $view->setVar('szerzo3list', $pc->getSelectList($anyag?->getSzerzo3Id()));
        $view->setVar('szerzo4list', $pc->getSelectList($anyag?->getSzerzo4Id()));
        $view->setVar('szerzo5list', $pc->getSelectList($anyag?->getSzerzo5Id()));

        $ac = new mptngyszakmaianyagController($this->params);
        $view->setVar('eloadas1list', $ac->getSelectList($anyag?->getEloadas1Id()));
        $view->setVar('eloadas2list', $ac->getSelectList($anyag?->getEloadas2Id()));
        $view->setVar('eloadas3list', $ac->getSelectList($anyag?->getEloadas3Id()));
        $view->setVar('eloadas4list', $ac->getSelectList($anyag?->getEloadas4Id()));
        $view->setVar('eloadas5list', $ac->getSelectList($anyag?->getEloadas5Id()));

        $bc = new dolgozoController($this->params);
        $view->setVar('biralo1list', $bc->getSelectList($anyag?->getBiralo1Id()));
        $view->setVar('biralo2list', $bc->getSelectList($anyag?->getBiralo2Id()));
        $view->setVar('biralo3list', $bc->getSelectList($anyag?->getBiralo3Id()));


        $view->setVar('egyed', $this->loadVars($anyag, true));
        $view->printTemplateResult();
    }

    /**
     * @param $selid
     * @param FilterDescriptor | array $filter
     *
     * @return array
     */
    public function getSelectList($selid = null, $filter = array())
    {
        $f = new FilterDescriptor();
        $rec = $this->getRepo()->getAll($f, array('cim' => 'ASC'));
        $res = array();
        foreach ($rec as $sor) {
            $res[] = array(
                'id' => $sor->getId(),
                'caption' => $sor->getCim(),
                'selected' => ($sor->getId() == $selid)
            );
        }
        return $res;
    }

    public function showSzakmaianyagok() {
        if ($this->getRepo(Partner::class)->checkloggedin()) {
            $v = $this->createMainView('anyaglist.tpl');
            $v->printTemplateResult();
        } else {
            $pc = new partnerController($this->params);
            $pc->showLoginForm();
        }
    }

    public function getAnyagList() {
        $res = [];
        $partner = $this->getRepo(Partner::class)->getLoggedInUser();
        if ($partner) {
            $filter = new FilterDescriptor();
            $filter->addSql(
                '(_xx.tulajdonos=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo1=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo2=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo3=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo4=' . $partner->getId() . ') OR ' .
                '(_xx.szerzo5=' . $partner->getId() . ')'
            );

            $anyagok = $this->getRepo()->getAll($filter);
            foreach ($anyagok as $anyag) {
                $res[] = $this->loadVars($anyag);
            }
        }
        echo json_encode($res);
    }

    public function getSajatAnyagList() {
        $res = [];
        $partner = $this->getRepo(Partner::class)->getLoggedInUser();
        if ($partner) {
            $filter = new FilterDescriptor();
            $filter->addFilter('tulajdonos', '=', $partner);

            $anyagok = $this->getRepo()->getAll($filter);
            foreach ($anyagok as $anyag) {
                $res[] = $this->loadVars($anyag);
            }
        }
        echo json_encode($res);
    }

}
