<?php

namespace Controllers;


class partnertermekkedvezmenyController extends \mkwhelpers\MattableController {

    public function __construct($params) {
        $this->setEntityName('Entities\PartnerTermekKedvezmeny');
        parent::__construct($params);
    }

    /**
     * @param \Entities\PartnerTermekKedvezmeny $t
     * @param bool|false $forKarb
     * @return array
     */
    public function loadVars($t, $forKarb = false) {
        $tc = new termekController($this->params);
        $x = array();
        if (!$t) {
            $t = new \Entities\PartnerTermekKedvezmeny();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        }
        else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['termek'] = $t->getTermek();
        $x['termeknev'] = $t->getTermekNev();
        $x['termekcikkszam'] = $t->getTermekCikkszam();
        $x['kedvezmeny'] = $t->getKedvezmeny();
        if ($forKarb) {
            $x['termeklist'] = $tc->getSelectList(($t->getTermek() ? $t->getTermekId() : 0));
        }
        return $x;
    }

    public function getemptyrow() {
        $view = $this->createView('partnertermekkedvezmenykarb.tpl');
        $view->setVar('kd', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getFiokList($newpartner = false) {
        if (!$newpartner) {
            $l = $this->getRepo()->getForFiok($this->getRepo('Entities\Partner')->getLoggedInUser());
        }
        else {
            $l = $this->getRepo()->getForFiok();
        }
        $ret = array();
        $db = 0;
        foreach ($l as $it) {
            $db++;
            $ret[] = array(
                'id' => ($it['id'] ? $it['id'] : 'new' . $db),
                'oper' => ($it['id'] ? 'edit' : 'add'),
                'tid' => $it['tid'],
                'nev' => $it['nev'],
                'kedvezmeny' => ($it['kedvezmeny'] ? $it['kedvezmeny'] * 1 : '')
            );
        }
        return $ret;
    }

}