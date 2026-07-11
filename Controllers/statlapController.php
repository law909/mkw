<?php

namespace Controllers;

use Entities\Statlap;

class statlapController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Statlap::class);
        $this->setKarbFormTplName('statlapkarbform.tpl');
        $this->setKarbTplName('statlapkarb.tpl');
        $this->setListBodyRowTplName('statlaplista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new \Entities\Statlap();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        return $x;
    }

    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj, [
            'raw' => ['szoveg', 'szoveg_l1'],
        ]);
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('statlaplista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('oldalcim', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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

    public function viewselect()
    {
        $view = $this->createView('statlaplista.tpl');

        $view->setVar('pagetitle', t('Statikus lapok'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('statlaplista.tpl');

        $view->setVar('pagetitle', t('Statikus lapok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Statikus lap'));
        $view->setVar('formaction', '/admin/statlap/save');
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getstatlap(Statlap $statlap)
    {
        $t = [];
        $t['szoveg'] = $statlap->getLocalizedFieldValue('szoveg');
        $t['oldalcim'] = $statlap->getLocalizedFieldValue('oldalcim');
        return $t;
    }

    public function show()
    {
        $com = $this->params->getStringParam('lap');
        /** @var Statlap $statlap */
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('statlap.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('pagetitle', $statlap->getShowOldalcim());
            $view->setVar('seodescription', $statlap->getShowSeodescription());
            $view->setVar('statlap', $this->getstatlap($statlap));
            $view->printTemplateResult(true);
        } else {
            \mkw\store::redirectTo404($com, $this->params);
        }
    }

    public function showPopup()
    {
        $com = $this->params->getStringParam('lap');
        /** @var Statlap $statlap */
        $statlap = $this->getRepo()->findOneBySlug($com);
        if ($statlap) {
            $view = $this->getTemplateFactory()->createMainView('statlappopup.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('szoveg', $statlap->getLocalizedFieldValue('szoveg'));
            $view->printTemplateResult(false);
        } else {
            echo '';
        }
    }

    public function redirectOldUrl()
    {
        $lapid = $this->params->getStringRequestParam('page');
        if ($lapid) {
            switch ($lapid) {
                case 'hirek':
                    $newlink = \mkw\store::getRouter()->generate('showhirlist', false, []);
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . $newlink);
                    return;
                case 'markak':
                    $newlink = \mkw\store::getRouter()->generate('markak', false, []);
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . $newlink);
                    return;
                default:
                    $lap = $this->getRepo()->findOneByOldurl($lapid);
                    if ($lap) {
                        $newlink = \mkw\store::getRouter()->generate('showstatlap', false, ['lap' => $lap->getSlug()]);
                        header("HTTP/1.1 301 Moved Permanently");
                        header('Location: ' . $newlink);
                        return;
                    }
            }
        }
        $mc = new mainController();
        $mc->show404('HTTP/1.1 410 Gone');
    }

    public function getSelectList($selid)
    {
        $rec = $this->getRepo()->getAll([], ['oldalcim' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getOldalcim(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

}