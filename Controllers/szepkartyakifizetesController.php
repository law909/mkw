<?php

namespace Controllers;


class szepkartyakifizetesController extends \mkwhelpers\MattableController {

    public function view() {
        $view = $this->createView('szepkartyakifizetes.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('fizmod', '=', \mkw\store::getParameter(\mkw\consts::SZEPFizmod))
            ->addFilter('szepkartyakifizetve', '=', false);

        $res = $this->getRepo('Entities\Bizonylatfej')->getAll($filter);
        $arr = array();
        foreach ($res as $o) {
            $arr[] = $o->toLista();
        }

        $view->setVar('tomb', $arr);
        $view->printTemplateResult();
    }

    public function kifizet() {
        $id = $this->params->getStringRequestParam('id');
        /** @var \Entities\Bizonylatfej $biz */
        $biz = $this->getRepo('Entities\Bizonylatfej')->find($id);
        if ($biz) {
            $biz->setSzepkartyakifizetve(true);
            $biz->setSimpleedit(true);
            $this->getEm()->persist($biz);
            $this->getEm()->flush();
        }
    }

}