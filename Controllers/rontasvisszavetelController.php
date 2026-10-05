<?php

namespace Controllers;

/**
 * Rontott bizonylat rontásának visszavétele (Egyéb műveletek); a munka a
 * \Services\BizonylatRontasVisszavetelService-ben van.
 */
class rontasvisszavetelController extends \mkwhelpers\Controller
{

    private const MENUURL = '/admin/rontasvisszavetel/view';
    private const JOG = 999;

    public function view()
    {
        if (!\mkw\store::haveMenuJog(self::MENUURL, self::JOG)) {
            return;
        }
        $view = $this->createView('rontasvisszavetel.tpl');
        $view->setVar('pagetitle', t('Rontás visszavétele'));
        $view->printTemplateResult();
    }

    public function info()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!\mkw\store::haveMenuJog(self::MENUURL, self::JOG)) {
            $this->jsonFail(t('Nincs jogosultsága.'));
            return;
        }
        echo json_encode(
            (new \Services\BizonylatRontasVisszavetelService())->getInfo(trim($this->params->getStringRequestParam('id')))
        );
    }

    public function restore()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!\mkw\store::haveMenuJog(self::MENUURL, self::JOG)) {
            $this->jsonFail(t('Nincs jogosultsága.'));
            return;
        }
        echo json_encode(
            (new \Services\BizonylatRontasVisszavetelService())->restore(
                trim($this->params->getStringRequestParam('id')),
                $this->params->getArrayRequestParam('penztar'),
                $this->params->getArrayRequestParam('bank'),
                $this->params->getBoolRequestParam('szarmaztatott')
            )
        );
    }
}
