<?php

namespace Controllers;

use Entities\Partnertelephely;

/**
 * A partner telephelyei a partner karbantartó „Telephelyek” fülén. Külön listája nincs: mindig
 * a partnerrel együtt mentődik, a törlés megy csak ezen a kontrolleren.
 */
class partnertelephelyController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Partnertelephely::class);
        parent::__construct();
    }

    /**
     * @param \Entities\Partnertelephely $t
     * @param bool $forKarb
     *
     * @return array
     */
    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new Partnertelephely();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = \mkw\store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x['nev'] = $t->getNev();
        $x['irszam'] = $t->getIrszam();
        $x['varos'] = $t->getVaros();
        $x['utca'] = $t->getUtca();
        $x['migrid'] = $t->getMigrid();
        if ($forKarb) {
            $x['orszaglist'] = (new orszagController())->getSelectList($t->getOrszagId());
        }
        return $x;
    }

    protected function setFields($obj)
    {
        return $this->setEntityFieldsFromRequest($obj);
    }

    public function getemptyrow()
    {
        $view = $this->createView('partnertelephelykarb.tpl');
        $view->setVar('tp', $this->loadVars(null, true));
        echo $view->getTemplateResult();
    }

    /**
     * A partner telephelyei a webshop választójához.
     *
     * @return array
     */
    public function getSelectList($partner, $selid = null)
    {
        $res = [];
        /** @var Partnertelephely $telephely */
        foreach ($this->getRepo()->getByPartner($partner) as $telephely) {
            $res[] = [
                'id' => $telephely->getId(),
                'caption' => $telephely->getNevCim(),
                'selected' => ($telephely->getId() == $selid),
            ];
        }
        return $res;
    }

}
