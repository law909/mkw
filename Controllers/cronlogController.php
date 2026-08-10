<?php

namespace Controllers;

use Entities\Cronlog;
use Services\CronService;

/**
 * A cron futások naplója. Csak nézegetni és törölni lehet – a sorokat a
 * `Services\CronService` írja, felvinni és szerkeszteni értelmetlen volna.
 */
class cronlogController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Cronlog::class);
        $this->setKarbFormTplName('cronlogkarbform.tpl');
        $this->setKarbTplName('cronlogkarb.tpl');
        $this->setListBodyRowTplName('cronloglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Cronlog();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['kezdet'] = $t->getKezdetStr();
        $x['veg'] = $t->getVegStr();
        $x['idotartam'] = $t->getIdotartamStr();
        return $x;
    }

    /**
     * A napló nem szerkeszthető: a mentés útvonala csak a törlést szolgálja ki, ami a
     * MattableController::saveData()-ban ide be sem néz.
     */
    protected function setFields($obj)
    {
        throw new \RuntimeException('A cron napló nem szerkeszthető.');
    }

    public function getlistbody()
    {
        $view = $this->createView('cronloglista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getStringRequestParam('feladatfilter')) {
            $filter->addFilter('feladat', '=', $this->params->getStringRequestParam('feladatfilter'));
        }
        if ($this->params->getStringRequestParam('allapotfilter')) {
            $filter->addFilter('allapot', '=', $this->params->getStringRequestParam('allapotfilter'));
        }
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('uzenet', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('cronloglista.tpl');

        $view->setVar('pagetitle', t('Cron napló'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->setVar('feladatselect', $this->getFeladatList());
        $view->setVar('allapotselect', $this->getAllapotList());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Cron napló'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('admincronlogsave'));
        $view->setVar('oper', $oper);
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($id), true));
        return $view->getTemplateResult();
    }

    /**
     * A regiszterben szereplő feladatok és a naplóban előforduló nevek uniója: a közben
     * megszűnt feladat sorait is ki lehessen szűrni.
     */
    private function getFeladatList()
    {
        $nevek = array_unique(array_merge(
            array_keys((new CronService())->getTaskList()),
            $this->getRepo()->getFeladatList()
        ));
        sort($nevek);
        return $nevek;
    }

    private function getAllapotList()
    {
        return [
            Cronlog::ALLAPOTOK,
            Cronlog::ALLAPOTFIGYELEM,
            Cronlog::ALLAPOTHIBA,
            Cronlog::ALLAPOTZAROLT,
            Cronlog::ALLAPOTFUT
        ];
    }
}
