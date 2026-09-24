<?php

namespace Controllers;

use Entities\Berjogcim;
use Entities\Dolgozober;

class berjogcimController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Berjogcim::class);
        $this->setKarbFormTplName('berjogcimkarbform.tpl');
        $this->setKarbTplName('berjogcimkarb.tpl');
        $this->setListBodyRowTplName('berjogcimlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Berjogcim();
            $this->getEm()->detach($t);
        }
        return $this->getEntityFieldsArray($t);
    }

    /**
     * @param \Entities\Berjogcim $obj
     *
     * @return \Entities\Berjogcim
     */
    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    protected function beforeRemove($o)
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('berjogcim', '=', $o);
        if ($this->getRepo(Dolgozober::class)->getCount($filter)) {
            throw new \mkwhelpers\Exceptions\UserMessageException(
                t('A jogcímen van rögzített bér, ezért nem törölhető. Inaktívvá teheti.')
            );
        }
    }

    public function getlistbody()
    {
        $view = $this->createView('berjogcimlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager($this->getRepo()->getCount($filter));

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
        $view = $this->createView('berjogcimlista.tpl');

        $view->setVar('pagetitle', t('Bér jogcímek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Bér jogcím'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminberjogcimsave'));
        $view->setVar('oper', $oper);
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

    /** Active titles, plus $selid even when inactive so an old line still shows its own. */
    public function getSelectList($selid = null)
    {
        $res = [];
        foreach ($this->getRepo()->getAll([], ['nev' => 'ASC']) as $sor) {
            if ($sor->getInaktiv() && $sor->getId() != $selid) {
                continue;
            }
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev(), 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    /** Every title for the list filter: old lines must be findable by an inactive one too. */
    public function getFilterSelectList()
    {
        $res = [];
        foreach ($this->getRepo()->getAll([], ['nev' => 'ASC']) as $sor) {
            $res[] = ['id' => $sor->getId(), 'caption' => $sor->getNev() . ($sor->getInaktiv() ? ' (' . t('inaktív') . ')' : '')];
        }
        return $res;
    }
}
