<?php

namespace Controllers;

use Entities\Dolgozo;
use Entities\MPTNGYSzakmaianyag;
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
        if ($forKarb) {
            $kedv = array();
            foreach ($t->getTermekcsoportkedvezmenyek() as $tar) {
                $kedv[] = $kedvCtrl->loadVars($tar, true);
            }
            $x['termekcsoportkedvezmenyek'] = $kedv;
            $kedv = array();
            foreach ($t->getTermekkedvezmenyek() as $tar) {
                $kedv[] = $termekkedvCtrl->loadVars($tar, true);
            }
            $x['termekkedvezmenyek'] = $kedv;

            $dok = array();
            foreach ($t->getPartnerDokok() as $kepje) {
                $dok[] = $dokCtrl->loadVars($kepje);
            }
            $x['dokok'] = $dok;
        }

        if (\mkw\store::isMPT()) {
            $fsz = [];
            foreach ($t->getMPTFolyoszamlak() as $item) {
                $fsz[] = $mptfolyoszamlaCtrl->loadVars($item, true);
            }
            $x['mptfolyoszamla'] = $fsz;
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

        echo json_encode($this->loadDataToView($egyedek, 'lista', $view));
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

}
