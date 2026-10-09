<?php

namespace Traits;

use Entities\Bankbizonylatfej;
use Entities\Bizonylatnaplo;

/**
 * A bank- és a pénztárbizonylat naplójának kontroller-oldala: a mentés eseménye és a napló
 * megjelenítése. A mezőváltozásokat a listenerek naplózzák (Listeners\PenzmozgasNaplo).
 */
trait PenzmozgasNaplozas
{

    protected function afterSave($o, $parancs = null)
    {
        parent::afterSave($o, $parancs);
        // a listener a változásokat írja; a mentés ténye akkor is a bizonylat története, ha semmi nem változott
        if (!$o || !$o->getId() || ($parancs !== $this->editOperation)) {
            return;
        }
        $naplo = new Bizonylatnaplo();
        $naplo->setPenzmozgas($o);
        $naplo->setCreated(new \DateTime());
        $naplo->setDolgozo(\mkw\store::getLoggedInDolgozo());
        $naplo->setDolgozonev(\mkw\store::getLoggedInDolgozoNev());
        $naplo->setEsemeny(Bizonylatnaplo::ESEMENY_MENTES);
        $naplo->setEsemenynev(t('Mentés'));
        $this->getEm()->persist($naplo);
        $this->getEm()->flush();
    }

    public function getNaplo()
    {
        $id = $this->params->getStringRequestParam('id');
        $repo = $this->getRepo(Bizonylatnaplo::class);
        $naplok = $this->getEntityName() === Bankbizonylatfej::class
            ? $repo->getByBankbizonylatfej($id)
            : $repo->getByPenztarbizonylatfej($id);
        $adat = [];
        foreach ($naplok as $naplo) {
            $adat[] = [
                'datum' => $naplo->getCreatedStr(),
                'dolgozonev' => $naplo->getDolgozonev(),
                'esemenynev' => $naplo->getEsemenynev(),
                'regi' => $naplo->getRegiertek(),
                'uj' => $naplo->getUjertek(),
            ];
        }
        $view = $this->createView('bizonylatnaploreszletezo.tpl');
        $view->setVar('lista', $adat);
        $view->printTemplateResult();
    }

}
