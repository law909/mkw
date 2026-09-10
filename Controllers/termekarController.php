<?php

namespace Controllers;

use Entities\Termek;
use Entities\TermekAr;
use mkw\store;

class termekarController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(TermekAr::class);
        parent::__construct();
    }

    /**
     * @param Termek|null $termek a képlet költségválasztékának szűkítéséhez; új sornál a $t-n még
     *                            nincs termék, ezért kell külön
     */
    public function loadVars($t, $forKarb = false, $termek = null)
    {
        $valutanem = new valutanemController();
        $arsav = new arsavController();
        $x = [];
        if (!$t) {
            $t = new \Entities\TermekAr();
            $this->getEm()->detach($t);
            $x['oper'] = 'add';
            $x['id'] = store::createUID();
        } else {
            $x['oper'] = 'edit';
            $x['id'] = $t->getId();
        }
        $x = $this->getEntityFieldsArray($t, $x);
        if ($forKarb) {
            $x['valutanemlist'] = $valutanem->getSelectList($t->getValutanem()?->getId());
            $x['arsavlist'] = $arsav->getSelectList($t->getArsav()?->getId());
            $x['forrasarsavlist'] = $arsav->getSelectList($t->getForrasarsavId());
            // a képlethez csak a termékhez rendelt kapcsolódó költségek választhatók
            $kkc = new kapcsolodokoltsegController();
            $x['kepletkoltseglist'] = $kkc->getSelectList(
                $t->getAllKepletkoltsegId(),
                [],
                ($termek ?: $t->getTermek())?->getAllKapcsolodokoltsegId() ?? []
            );
        }
        return $x;
    }

    protected function setFields($obj)
    {
        $ck = store::getEm()->getRepository(Termek::class)->find($this->params->getIntRequestParam('altermek'));
        if ($ck) {
            $obj->setAlTermek($ck);
        }
        return $obj;
    }

    public function getemptyrow()
    {
        $view = $this->createView('termektermekarkarb.tpl');
        // a termék a formról jön: az új ársáv sor költségválasztékát is szűkíteni kell
        $termek = $this->getRepo(Termek::class)->find($this->params->getIntRequestParam('termekid'));
        $view->setVar('ar', $this->loadVars(null, true, $termek));
        echo $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getExistingArsavok();
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor['id'],
                'caption' => $sor['azonosito'],
                'selected' => ($sor['azonosito'] == $selid),
                'valutanemid' => $sor['valutanemid'],
                'valutanem' => $sor['valutanem']
            ];
        }
        return $res;
    }
}
