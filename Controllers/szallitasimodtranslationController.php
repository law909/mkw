<?php

namespace Controllers;

use mkw\store;

class szallitasimodtranslationController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\SzallitasimodTranslation');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\SzallitasimodTranslation('','','');
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['locale'] = $t->getLocale();
        $x['content'] = $t->getContent();
        $x['field'] = $t->getField();
        $f =  \Entities\Szallitasimod::getTranslatedFields();
        $x['type'] = $f[$t->getField()]['type'];
        if ($forKarb) {
            $x['fieldlist'] = \Entities\Szallitasimod::getTranslatedFieldsSelectList($t->getField());
        }
        return $x;
    }

    protected function setFields($obj) {
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('translationkarb.tpl');
        $view->setVar('translation', $this->loadVars(null, true));
        $view->setVar('locale', '');
        echo $view->getTemplateResult();
    }

}
