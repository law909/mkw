<?php
namespace Controllers;
use mkw\store;

class exportController extends \mkwhelpers\Controller {

    public function view() {
        $view = $this->createView('imports.tpl');
        $view->setVar('pagetitle', t('Importok'));
        $view->printTemplateResult(false);
    }

    public function kreativpuzzleImport() {
        $termekurl = 'http://kreativpuzzle.hu/lawstocklist';
        $kepurl = 'http://kreativpuzzle.hu/lawstocklist/images.php';
    }

}