<?php

namespace Controllers;

use Entities\Partner;
use Entities\PartnerTermekkategoriaKedvezmeny;

class partnertermekkategoriakedvezmenyController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(PartnerTermekkategoriaKedvezmeny::class);
        parent::__construct();
    }

    /**
     * @param PartnerTermekkategoriaKedvezmeny|null $t
     * @param bool $forKarb
     *
     * @return array
     */
    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new PartnerTermekkategoriaKedvezmeny();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['termekfa'] = $t->getTermekfaId();
        $x['termekfanev'] = $t->getTermekfaNev();
        $x['kedvezmeny'] = $t->getKedvezmeny();
        return $x;
    }

    public function getemptyrow()
    {
        $view = $this->createView('partnertermekkategoriakedvezmenykarb.tpl');
        $view->setVar('kd', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    public function getFiokList($newpartner = false)
    {
        $rows = $this->getRepo()->getForFiok($newpartner ? null : $this->getRepo(Partner::class)->getLoggedInUser());
        $ret = [];
        $counter = 0;
        foreach ($rows as $row) {
            $counter++;
            $ret[] = [
                'id' => ($row['id'] ?: 'new' . $counter),
                'oper' => ($row['id'] ? 'edit' : 'add'),
                'termekfaid' => $row['termekfaid'],
                'nev' => $row['nev'],
                'kedvezmeny' => ($row['kedvezmeny'] ? (float)$row['kedvezmeny'] : '')
            ];
        }
        return $ret;
    }

}
