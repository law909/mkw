<?php

namespace Controllers;

use mkw\store;

class termektranslationController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\Termek');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false) {
        $x = array();
        return $x;
    }

    protected function setFields($obj) {
        return $obj;
    }

    public function getemptyrow() {
        $view = $this->createView('termektermektranslationkarb.tpl');
        $view->setVar('translation', $this->loadVars(null, true));
        $view->setVar('locale', store::createGUID());
        echo $view->getTemplateResult();
    }

    public function delete() {
        $parancs = $this->params->getRequestParam($this->operationName, '');
        $id = $this->params->getRequestParam($this->idName, 0);
        $termekid = $this->params->getRequestParam('termekid', 0);
        switch ($parancs) {
            case $this->delOperation:
                $obj = $this->getRepo()->find($termekid);
                if ($obj) {
                    $obj->setNev(null);
                    $obj->setOldalcim(null);
                    $obj->setLeiras(null);
                    $obj->setRovidleiras(null);
                    $obj->setTranslatableLocale($id);
                    $this->getEm()->persist($obj);
                    $this->getEm()->flush();
                }
                break;
        }
        echo $id;
    }

}
