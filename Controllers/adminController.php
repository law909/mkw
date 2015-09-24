<?php

namespace Controllers;

use mkw\ArCalculator;
use mkwhelpers,
    mkw\store;
use Entities;

class adminController extends mkwhelpers\Controller {

    private function checkForIE() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = false;
        if (preg_match('/MSIE/i', $u_agent)) {
            $view = $this->createView('noie.tpl');
            $this->generalDataLoader->loadData($view);
            $view->printTemplateResult();
            $ub = true;
        }
        return $ub;
    }

    public function view() {
        $view = $this->createView('main.tpl');
        $this->generalDataLoader->loadData($view);
        $view->setVar('pagetitle', t('Főoldal'));

        $raktar = new raktarController($this->params);
        $raktarid = store::getParameter(\mkw\consts::Raktar, 0);
        $view->setVar('raktarlist', $raktar->getSelectList($raktarid));

        $view->printTemplateResult();
    }

    public function regeneratekarkod() {
        $farepo = store::getEm()->getRepository('Entities\TermekFa');
        $farepo->regenerateKarKod();
        echo 'ok';
    }

    public function sanitize() {
        echo \mkwhelpers\Filter::toPermalink($this->params->getStringRequestParam('text', ''));
    }

    protected function cropimage() {
        $view = $this->createView('cropimage.tpl');
        $this->generalDataLoader->loadData($view);
        $view->setVar('pagetitle', t('Főoldal'));
        $view->printTemplateResult();
    }

    public function setUITheme() {
        store::setParameter('uitheme', $this->params->getStringRequestParam('uitheme', 'sunny'));
    }

    public function getSmallUrl() {
        echo \mkw\Store::createSmallImageUrl($this->params->getStringRequestParam('url'));
    }

    protected function arcalctest() {
        $t = store::getEm()->getRepository('Entities\Termek')->find(7);
        $v = store::getEm()->getRepository('Entities\Valutanem')->find(1);
        $p = store::getEm()->getRepository('Entities\Partner')->find(1);
        $ac = new ArCalculator($v, $p, $t);
        echo $ac->getPartnerAr();
    }

    public function setVonalkodFromValtozat() {
        $filter['fields'][] = 'vonalkod';
        $filter['clauses'][] = '=';
        $filter['values'][] = '';
        $termekek = store::getEm()->getRepository('Entities\Termek')->getAll($filter, array());
        foreach($termekek as $termek) {
            $valtozatok = $termek->getValtozatok();
            $termek->setVonalkod($valtozatok[0]->getVonalkod());
            store::getEm()->persist($termek);
            store::getEm()->flush();
        }
        echo 'ok';
    }

}
