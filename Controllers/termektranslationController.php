<?php

namespace Controllers;

use mkw\store;

class termektranslationController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\TermekTranslation');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekTranslation('', '', '');
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['locale'] = $t->getLocale();
        $x['content'] = $t->getContent();
        $x['field'] = $t->getField();
        $f = \Entities\Termek::getTranslatedFields();
        $x['type'] = $f[$t->getField()]['type'];
        if ($forKarb) {
            $x['fieldlist'] = \Entities\Termek::getTranslatedFieldsSelectList($t->getField());
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('translationkarb.tpl');
        $view->setVar('translation', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    protected function afterSave($o, $parancs = null)
    {
        switch ($parancs) {
            case $this->delOperation:
                $o->getTermek()?->clearWcdate();
                $o->getTermek()?->uploadToWC();
        }
        parent::afterSave($o, $parancs);
    }

}
