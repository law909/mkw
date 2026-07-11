<?php

namespace Controllers;


use Entities\Bizonylatfej;

class szepkartyakifizetesController extends \mkwhelpers\MattableController
{

    public function view()
    {
        $view = $this->createView('szepkartyakifizetes.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        $filter
            ->addFilter('fizmod', '=', \mkw\store::getParameter(\mkw\consts::SZEPFizmod))
            ->addFilter('szepkartyakifizetve', '=', false);

        $res = $this->getRepo(Bizonylatfej::class)->getAll($filter);
        $arr = [];
        foreach ($res as $o) {
            $arr[] = $o->toLista();
        }

        $view->setVar('tomb', $arr);
        $view->printTemplateResult();
    }

    public function kifizet()
    {
        $id = $this->params->getStringRequestParam('id');
        /** @var \Entities\Bizonylatfej $biz */
        $biz = $this->getRepo(Bizonylatfej::class)->find($id);
        if ($biz) {
            $biz->setSzepkartyakifizetve(true);
            $biz->setSimpleedit(true);
            $this->getEm()->persist($biz);
            $this->getEm()->flush();
        }
    }

}