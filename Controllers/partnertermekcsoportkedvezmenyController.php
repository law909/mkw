<?php

namespace Controllers;


use Entities\PartnerTermekcsoportKedvezmeny;

class partnertermekcsoportkedvezmenyController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(PartnerTermekcsoportKedvezmeny::class);
        parent::__construct();
    }

    /**
     * @param \Entities\PartnerTermekcsoportKedvezmeny $t
     * @param bool|false $forKarb
     *
     * @return array
     */
    public function loadVars($t, $forKarb = false)
    {
        $tcs = new termekcsoportController();
        $x = [];
        if (!$t) {
            $t = new \Entities\PartnerTermekcsoportKedvezmeny();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
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

    public function getemptyrow()
    {
        $view = $this->createView('partnertermekcsoportkedvezmenykarb.tpl');
        $view->setVar('kd', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getFiokList($newpartner = false)
    {
        if (!$newpartner) {
            $l = $this->getRepo()->getForFiok($this->getRepo('Entities\Partner')->getLoggedInUser());
        } else {
            $l = $this->getRepo()->getForFiok();
        }
        $ret = [];
        $db = 0;
        foreach ($l as $it) {
            $db++;
            $ret[] = [
                'id' => ($it['id'] ? $it['id'] : 'new' . $db),
                'oper' => ($it['id'] ? 'edit' : 'add'),
                'tcsid' => $it['tcsid'],
                'nev' => $it['nev'],
                'kedvezmeny' => ($it['kedvezmeny'] ? (float)$it['kedvezmeny'] : '')
            ];
        }
        return $ret;
    }

}