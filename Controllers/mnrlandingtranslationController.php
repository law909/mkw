<?php

namespace Controllers;

use Entities\MNRLandingTranslation;
use mkw\store;

class mnrlandingtranslationController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName(MNRLandingTranslation::class);
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $x = array();
        if (!$t) {
            $t = new \Entities\MNRLandingTranslation('','','');
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
        $f =  \Entities\MNRLanding::getTranslatedFields();
        $x['type'] = $f[$t->getField()]['type'];
        if ($forKarb) {
            $x['fieldlist'] = \Entities\MNRLanding::getTranslatedFieldsSelectList($t->getField());
        }
        return $x;
    }

    protected function setFields($obj) {
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('translationkarb.tpl');
        $view->setVar('translation', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}
