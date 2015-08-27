<?php

namespace Controllers;

use mkw\store;

class fantaController extends \mkwhelpers\MattableController {

    public function show() {
		$this->view = $this->getTemplateFactory()->createMainView('fanta.tpl');
		$this->view->printTemplateResult(true);
    }
}
