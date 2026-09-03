<?php

namespace Controllers;

use Entities\Emailtemplate;
use Entities\Munkalapstatusz;

class munkalapstatuszController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Munkalapstatusz::class);
        $this->setKarbFormTplName('munkalapstatuszkarbform.tpl');
        $this->setKarbTplName('munkalapstatuszkarb.tpl');
        $this->setListBodyRowTplName('munkalapstatuszlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new Munkalapstatusz();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['emailtemplate'] = $t->getEmailtemplateId();
        $x['emailtemplatenev'] = $t->getEmailtemplateNev();
        return $x;
    }

    /** @param \Entities\Munkalapstatusz $obj */
    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj);
        $ck = $this->getRepo(Emailtemplate::class)->find($this->params->getIntRequestParam('emailtemplate'));
        if ($ck) {
            $obj->setEmailtemplate($ck);
        } else {
            $obj->removeEmailtemplate();
        }
        return $obj;
    }

    protected function validate($obj, $parancs)
    {
        if (!trim((string)$obj->getNev())) {
            return ['nev' => t('A név nem maradhat üresen.')];
        }
        return [];
    }

    public function getlistbody()
    {
        $view = $this->createView('munkalapstatuszlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

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
        $view = $this->createView('munkalapstatuszlista.tpl');

        $view->setVar('pagetitle', t('Munkalap státusz'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Munkalap státusz'));
        $view->setVar('formaction', '/admin/munkalapstatusz/save');
        $view->setVar('oper', $oper);
        /** @var \Entities\Munkalapstatusz $record */
        $record = $this->getRepo()->findWithJoins($id);
        $etpl = new emailtemplateController();
        $view->setVar('emailtemplatelist', $etpl->getSelectList($record ? $record->getEmailtemplateId() : 0));
        $view->setVar('egyed', $this->loadVars($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll(new \mkwhelpers\FilterDescriptor(), ['_xx.sorrend' => 'ASC', '_xx.nev' => 'ASC']);
        $res = [];
        /** @var \Entities\Munkalapstatusz $sor */
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => ($sor->getKod() ? $sor->getKod() . ' - ' : '') . $sor->getNev(),
                'selected' => ($sor->getId() == $selid),
                // a státuszváltáskor csak akkor van értelme email értesítést kérdezni,
                // ha a státuszhoz be van állítva email sablon
                'vanemailtemplate' => (bool)$sor->getEmailtemplateId(),
            ];
        }
        return $res;
    }

}
