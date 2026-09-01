<?php

namespace Controllers;

use Entities\PartnerGyartoKedvezmeny;

/**
 * A partner gyártónkénti kedvezményei. A gyártó maga is partner (szállító), ezért a választék
 * a szállítónak jelölt partnerekből áll.
 */
class partnergyartokedvezmenyController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(PartnerGyartoKedvezmeny::class);
        parent::__construct();
    }

    /**
     * @param \Entities\PartnerGyartoKedvezmeny $t
     * @param bool $forKarb
     *
     * @return array
     */
    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new PartnerGyartoKedvezmeny();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['gyarto'] = $t->getGyartoId();
        $x['gyartonev'] = $t->getGyartoNev();
        $x['kedvezmeny'] = $t->getKedvezmeny();
        if ($forKarb) {
            // ugyanaz a szállítólista, amit a termék és a címke karbantartó is használ
            $x['gyartolist'] = (new partnerController())->getGyartoSelectList($t->getGyartoId());
        }
        return $x;
    }

    public function getemptyrow()
    {
        $view = $this->createView('partnergyartokedvezmenykarb.tpl');
        $view->setVar('kd', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

}
