<?php

namespace Controllers;

use mkw\store;

class pagecacheController extends \mkwhelpers\Controller
{
    private const MENUURL = '/admin/pagecache/view';
    private const JOG = 90;

    public function view()
    {
        if (!store::haveMenuJog(self::MENUURL, self::JOG)) {
            return;
        }
        $view = $this->createView('pagecache.tpl');
        $view->setVar('pagetitle', t('Pagecache törlés'));
        $view->setVar('bekapcsolva', \mkw\pagecache::enabled());
        $view->setVar('fajldb', \mkw\pagecache::countFiles());
        $view->setVar('torolve', $this->params->existsRequestParam('torolve') ? $this->params->getIntRequestParam('torolve') : null);
        $view->printTemplateResult();
    }

    public function clear()
    {
        if (!store::haveMenuJog(self::MENUURL, self::JOG)) {
            return;
        }
        $db = \mkw\pagecache::clear();
        header('Location: ' . self::MENUURL . '?torolve=' . $db);
    }
}
