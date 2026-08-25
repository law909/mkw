<?php

namespace Controllers;

use Entities\Kapcsolodokoltseg;

class kapcsolodokoltsegController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Kapcsolodokoltseg::class);
        $this->setKarbFormTplName('kapcsolodokoltsegkarbform.tpl');
        $this->setKarbTplName('kapcsolodokoltsegkarb.tpl');
        $this->setListBodyRowTplName('kapcsolodokoltseglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Kapcsolodokoltseg();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['csoportnev'] = $t->getCsoportNev();
        $x['szamitasalapnev'] = $t->getSzamitasalapNev();
        if ($forKarb) {
            $x['csoportlist'] = $this->getRepo()->getCsoportList($t->getCsoport());
            $x['szamitasalaplist'] = $this->getRepo()->getSzamitasalapList($t->getSzamitasalap());
        }
        return $x;
    }

    /**
     * @param Kapcsolodokoltseg $obj
     */
    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj);
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('kapcsolodokoltseglista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $csoport = $this->params->getStringRequestParam('csoportfilter');
        if ($csoport) {
            $filter->addFilter('csoport', '=', $csoport);
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
        $view = $this->createView('kapcsolodokoltseglista.tpl');
        $view->setVar('pagetitle', t('Kapcsolódó költségek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->setVar('csoportlist', $this->getRepo()->getCsoportList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Kapcsolódó költség'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminkapcsolodokoltsegsave'));
        $view->setVar('oper', $oper);
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

    /**
     * A törzs teljes listája jelölőnégyzetekhez: a termékkarb és az árképlet is ezt használja.
     *
     * @param int[] $selids a bejelölt sorok azonosítói
     */
    public function getSelectList(array $selids = []): array
    {
        $res = [];
        /** @var Kapcsolodokoltseg $sor */
        foreach ($this->getRepo()->getAll([], ['csoport' => 'ASC', 'nev' => 'ASC']) as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'csoportnev' => $sor->getCsoportNev(),
                'szamitasalapnev' => $sor->getSzamitasalapNev(),
                'ar' => $sor->getAr(),
                'selected' => in_array($sor->getId(), $selids),
            ];
        }
        return $res;
    }

}
