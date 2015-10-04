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
                'tcsid' => $it['tcsid'],
                'nev' => $it['nev'],
                'kedvezmeny' => ($it['kedvezmeny'] ? $it['kedvezmeny'] * 1 : '')
            );
        }
        return $ret;
    }

}