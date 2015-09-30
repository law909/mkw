<?php

namespace Controllers;


class partnertermekcsoportkedvezmenyController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\PartnerTermekcsoportKedvezmeny');
        parent::__construct($params);
    }

    /**
     * @param \Entities\PartnerTermekcsoportKedvezmeny $t
     * @param bool|false $forKarb
     * @return array
     */
    public function loadVars($t, $forKarb = false) {
        $tcs = new termekcsoportController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\PartnerTermekcsoportKedvezmeny();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\Store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['termekcsoport'] = $t->getTermekcsoport();
        $x['termekcsoportnev'] = $t->getTermekcsoportNev();
        $x['kedvezmeny'] = $t->getKedvezmeny();
        if ($forKarb) {
            $x['termekcsoportlist'] = $tcs->getSelectList(($t->getTermekcsoport() ? $t->getTermekcsoportId() : 0));
        }
        return $x;
    }

    public function getemptyrow() {
        $view = $this->createView('partnertermekcsoportkedvezmenykarb.tpl');
        $view->setVar('kd', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}