<?php

namespace Controllers;

use Entities\Elallasnaplo;

class elallasnaploController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Elallasnaplo::class);
        parent::__construct();
    }

    public function loadVars($t)
    {
        $x = [];
        if (!$t) {
            $t = new Elallasnaplo();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x = $this->getEntityFieldsArray($t, $x);
        $x['esemenyido'] = $t->getEsemenyidoInput();
        $x['created'] = $t->getCreatedStr();
        return $x;
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getemptyrow()
    {
        $view = $this->createView('elallaselallasnaplokarb.tpl');
        $view->setVar('naplo', $this->loadVars(null));
        echo $view->getTemplateResult();
    }

    public function del()
    {
        /** @var Elallasnaplo $naplo */
        $naplo = $this->getRepo()->find($this->params->getNumRequestParam('id'));
        if ($naplo) {
            $this->getEm()->remove($naplo);
            $this->getEm()->flush();
        }
        echo $this->params->getNumRequestParam('id');
    }

}
