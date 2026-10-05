<?php

namespace Controllers;

use Entities\TermekValtozatAr;
use mkw\store;

class termekvaltozatarController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(TermekValtozatAr::class);
        parent::__construct();
    }

    /** @param string|int $valtozatid the variant's form key, the row inputs are grouped by it */
    public function loadVars($t, $forKarb = false, $valtozatid = null)
    {
        $x = [];
        if (!$t) {
            $t = new TermekValtozatAr();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['valtozatid'] = $valtozatid ?? $t->getTermekvaltozat()?->getId();
        $x['netto'] = $t->getNetto();
        $x['brutto'] = $t->getBrutto();
        if ($forKarb) {
            $x['valutanemlist'] = (new valutanemController())->getSelectList($t->getValutanemId());
            $x['arsavlist'] = (new arsavController())->getSelectList($t->getArsavId());
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('termekvaltozatarkarb.tpl');
        $view->setVar('ar', $this->loadVars(null, true, $this->params->getStringRequestParam('valtozatid')));
        echo $view->getTemplateResult();
    }
}
