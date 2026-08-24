<?php

namespace Controllers;

use Entities\Koltsegszamlaimportlog;
use mkwhelpers\FilterDescriptor;

/**
 * A NAV bejövő számla import naplója – csak megtekintésre. A bejegyzéseket a
 * {@see \Services\NAVKoltsegszamlaImportService} írja, itt szerkeszteni nem lehet, csak
 * kilistázni és törölni.
 */
class koltsegszamlaimportlogController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Koltsegszamlaimportlog::class);
        $this->setKarbFormTplName('koltsegszamlaimportlogkarbform.tpl');
        $this->setKarbTplName('koltsegszamlaimportlogkarb.tpl');
        $this->setListBodyRowTplName('koltsegszamlaimportloglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Koltsegszamlaimportlog();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['createdstr'] = $t->getCreatedStr();
        $x['idoszaktolstr'] = $t->getIdoszaktolStr();
        $x['idoszakigstr'] = $t->getIdoszakigStr();
        $x['hibas'] = $t->getHibas();
        return $x;
    }

    /**
     * A naplót a program írja; a képernyőn nincs mit menteni.
     *
     * @param Koltsegszamlaimportlog $obj
     */
    protected function setFields($obj)
    {
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('koltsegszamlaimportloglista_tbody.tpl');

        $filter = new FilterDescriptor();
        $szamlaszam = $this->params->getStringRequestParam('szamlaszamfilter');
        if ($szamlaszam) {
            $filter->addFilter('szamlaszam', 'LIKE', '%' . $szamlaszam . '%');
        }
        $szallito = $this->params->getStringRequestParam('szallitofilter');
        if ($szallito) {
            $filter->addFilter('szallito', 'LIKE', '%' . $szallito . '%');
        }
        $statusz = $this->params->getStringRequestParam('statuszfilter');
        if ($statusz) {
            $filter->addFilter('statusz', '=', $statusz);
        }
        // a hibás bejegyzések: a státusz mellett a fej- vagy tételszintű panasz is számít
        if ($this->params->getIntRequestParam('hibasfilter')) {
            $filter->addSql(
                "(_xx.statusz = 'hiba') OR (_xx.fejhiba IS NOT NULL AND _xx.fejhiba <> '')"
                . " OR (_xx.tetelhiba IS NOT NULL AND _xx.tetelhiba <> '')"
            );
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
        $view = $this->createView('koltsegszamlaimportloglista.tpl');

        $view->setVar('pagetitle', t('NAV import napló'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('NAV import napló'));
        $view->setVar('formaction', '');
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

}
