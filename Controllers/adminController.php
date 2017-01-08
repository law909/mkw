<?php

namespace Controllers;

use mkwhelpers, Entities;

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
        $raktarid = \mkw\store::getParameter(\mkw\consts::Raktar, 0);
        $view->setVar('raktarlist', $raktar->getSelectList($raktarid));

        $megrend = new megrendelesfejController($this->params);
        $view->setVar('teljesithetobackorderek', $megrend->getTeljesithetoBackorderLista());

        $lista = new listaController($this->params);
        switch (\mkw\store::getTheme()) {
            case 'superzone':
                $napijelentesdatum = date(\mkw\store::$DateFormat);
                $igdatum = date(\mkw\store::$DateFormat);
                $view->setVar('napijelenteslista', $lista->napiJelentes($napijelentesdatum, $igdatum));

                $lejart = array();
                $r = $this->getRepo('Entities\Folyoszamla')->getLejartKintlevosegByValutanem();
                foreach ($r as $_r) {
                    $lejart[$_r['nev']] = $_r;
                }

                if (\mkw\store::isFakeKintlevoseg()) {
                    $fake = $this->getRepo('Entities\Folyoszamla')->getFakeKintlevosegByValutanem();
                    foreach ($fake as $_r) {
                        if (array_key_exists($_r['nev'], $lejart)) {
                            $lejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                        }
                        else {
                            $lejart[$_r['nev']] = $_r;
                        }
                    }
                }
                $view->setVar('lejartkintlevoseg', $lejart);

                $nemlejart = array();
                $r = \mkw\store::getEm()->getRepository('Entities\Folyoszamla')->getKintlevosegByValutanem();
                foreach ($r as $_r) {
                    $nemlejart[$_r['nev']] = $_r;
                }
                if (\mkw\store::isFakeKintlevoseg()) {
                    foreach ($fake as $_r) {
                        if (array_key_exists($_r['nev'], $nemlejart)) {
                            $nemlejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                        }
                        else {
                            $nemlejart[$_r['nev']] = $_r;
                        }
                    }
                }
                $view->setVar('kintlevoseg', $nemlejart);

                $partnerkodok = \mkw\store::getEm()->getRepository('Entities\Partner')->getByCimkek(array(\mkw\store::getParameter(\mkw\consts::SpanyolCimke)));

                $lejart = array();
                $r = $this->getRepo('Entities\Folyoszamla')->getLejartKintlevosegByValutanem($partnerkodok);
                foreach ($r as $_r) {
                    $lejart[$_r['nev']] = $_r;
                }
                if (\mkw\store::isFakeKintlevoseg()) {
                    $fake = $this->getRepo('Entities\Folyoszamla')->getFakeKintlevosegByValutanem($partnerkodok);
                    foreach ($fake as $_r) {
                        if (array_key_exists($_r['nev'], $lejart)) {
                            $lejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                        }
                        else {
                            $lejart[$_r['nev']] = $_r;
                        }
                    }
                }
                $view->setVar('spanyollejartkintlevoseg', $lejart);

                $nemlejart = array();
                $r = \mkw\store::getEm()->getRepository('Entities\Folyoszamla')->getKintlevosegByValutanem($partnerkodok);
                foreach ($r as $_r) {
                    $nemlejart[$_r['nev']] = $_r;
                }
                if (\mkw\store::isFakeKintlevoseg()) {
                    foreach ($fake as $_r) {
                        if (array_key_exists($_r['nev'], $nemlejart)) {
                            $nemlejart[$_r['nev']]['egyenleg'] += $_r['egyenleg'] * 1;
                        }
                        else {
                            $nemlejart[$_r['nev']] = $_r;
                        }
                    }
                }
                $view->setVar('spanyolkintlevoseg', $nemlejart);

                break;
            case 'mkwcansas':
                $view->setVar('tjlista', $lista->teljesitmenyJelentes());
                break;
            case 'kisszamlazo':
                $view->setVar('lejartkintlevoseg', \mkw\store::getEm()->getRepository('Entities\Folyoszamla')->getLejartKintlevosegByValutanem());
                $view->setVar('kintlevoseg', \mkw\store::getEm()->getRepository('Entities\Folyoszamla')->getKintlevosegByValutanem());
                break;
            default:
                break;
        }
        $view->printTemplateResult();
    }

    public function printNapijelentes() {
        $lista = new listaController($this->params);
        $datumstr = $this->params->getStringRequestParam('datum');
        $datum = \mkw\store::convDate($datumstr);
        $igdatumstr = $this->params->getStringRequestParam('datumig');
        $igdatum = \mkw\store::convDate($igdatumstr);
        $view = $this->createView('napijelentesbody.tpl');
        $view->setVar('napijelenteslista', $lista->napiJelentes($datum, $igdatum));

        $view->printTemplateResult();
    }

    public function printTeljesitmenyJelentes() {
        $lista = new listaController($this->params);

        $datumstr = $this->params->getStringRequestParam('tol');
        $datum = \mkw\store::convDate($datumstr);
        $igdatumstr = $this->params->getStringRequestParam('ig');
        $igdatum = \mkw\store::convDate($igdatumstr);

        $view = $this->createView('teljesitmenyjelentesbody.tpl');
        $view->setVar('tjlista', $lista->teljesitmenyJelentes($datum, $igdatum));
        $view->printTemplateResult();
    }

    public function regeneratekarkod() {
        $farepo = \mkw\store::getEm()->getRepository('Entities\TermekFa');
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
        $dolgozo = $this->getRepo('Entities\Dolgozo')->find(\mkw\store::getAdminSession()->loggedinuser['id']);
        if ($dolgozo) {
            $theme = $this->params->getStringRequestParam('uitheme', 'sunny');
            $dolgozo->setUitheme($theme);
            $this->getEm()->persist($dolgozo);
            $this->getEm()->flush();
            \mkw\store::getAdminSession()->loggedinuser['uitheme'] = $theme;
        }
    }

    public function getSmallUrl() {
        echo \mkw\store::createSmallImageUrl($this->params->getStringRequestParam('url'));
    }

    public function setVonalkodFromValtozat() {
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('vonalkod', '=', '');
        $termekek = \mkw\store::getEm()->getRepository('Entities\Termek')->getAll($filter, array());
        foreach ($termekek as $termek) {
            $valtozatok = $termek->getValtozatok();
            $termek->setVonalkod($valtozatok[0]->getVonalkod());
            \mkw\store::getEm()->persist($termek);
            \mkw\store::getEm()->flush();
        }
        echo 'ok';
    }

    public function fillBiztetelValtozat() {
        $repo = $this->getRepo('Entities\Bizonylattetel');
        $mind = $repo->getAll();
        foreach ($mind as $bt) {
            if ($bt->getTermekvaltozat()) {
                $bt->setTermekvaltozat($bt->getTermekvaltozat());
                $this->getEm()->persist($bt);
                $this->getEm()->flush();
            }
        }
        echo 'kesz';
    }

    public function generateFolyoszamla() {
        /*
        $repo = $this->getRepo('Entities\Bizonylatfej');
        $filter = new \mkwhelpers\FilterDescriptor();
        $filter->addFilter('penztmozgat', '=', true);
        $bfs = $repo->getAll($filter);
        foreach ($bfs as $bf) {
            $repo->createFolyoszamla($bf);
        }

        $bbrepo = $this->getRepo('Entities\Bankbizonylatfej');
        $bfs = $bbrepo->getAll();
        foreach ($bfs as $bf) {
            $bbrepo->createFolyoszamla($bf);
        }
        */
        echo 'kesz';
    }

    public function minicrm() {
        require 'busvendor/MiniCRM/minicrm-api.phar';
        $minicrm = new \MiniCRM_Connection(\mkw\store::getParameter(\mkw\consts::MiniCRMSystemId), \mkw\store::getParameter(\mkw\consts::MiniCRMAPIKey));

        $res = \MiniCRM_Contact::FieldSearch($minicrm, array('Email' => 'balint.lovey@gmail.com'));
        if ($res['Count']) {
            $res = array_values($res['Results']);
            $balint = new \MiniCRM_Contact($minicrm, $res[0]['Id']);
            echo '<pre>';
            print_r($balint);
            echo '</pre>';

            $res = \MiniCRM_Address::AddressList($minicrm, $balint->Id);
            echo '<pre>';
            print_r($res);
            echo '</pre>';

            $res = \MiniCRM_Contact::GetSchema($minicrm, 'Person');
            echo '<pre>';
            print_r($res);
            echo '</pre>';
        }

        $res = \MiniCRM_Project::GetCategories($minicrm);
        echo '<pre>';
        print_r($res);
        echo '</pre>';

        $res = \MiniCRM_Project::GetSchema($minicrm, 7);
        echo '<pre>';
        print_r($res);
        echo '</pre>';

        $res = \MiniCRM_Project::FieldSearch($minicrm, array('ContactId' => $balint->Id, 'CategoryId' => 7));
        if ($res) {
            echo '<pre>';
            print_r($res);
            echo '</pre>';
            $res = array_values($res['Results']);
            $balintpr = new \MiniCRM_Project($minicrm, $res[0]['Id']);
            echo '<pre>';
            print_r($balintpr);
            echo '</pre>';
        }
    }

    public function genean13() {
        $conn = \mkw\store::getEm()->getConnection();
        $termekidk = \mkw\store::getEm()->getRepository('\Entities\Termek')->getIdsWithJoins(array(), array());
        foreach ($termekidk as $ttt) {
            $termekid = $ttt['id'];

            $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
            $stmt->execute();
            $vonalkod = \mkw\store::generateEAN13((string)$conn->lastInsertId());
            $st2 = $conn->prepare('UPDATE termek SET vonalkod="' . $vonalkod . '" WHERE id=' . $termekid);
            $st2->execute();

            $f = new \mkwhelpers\FilterDescriptor();
            $f->addFilter('termek', '=', $termekid);
            $valtozatok = \mkw\store::getEm()->getRepository('\Entities\TermekValtozat')->getAll($f);
            foreach ($valtozatok as $valtozat) {
                $stmt = $conn->prepare('INSERT INTO vonalkodseq (data) VALUES (1)');
                $stmt->execute();
                $vonalkod = \mkw\store::generateEAN13((string)$conn->lastInsertId());
                $st2 = $conn->prepare('UPDATE termekvaltozat SET vonalkod="' . $vonalkod . '" WHERE id=' . $valtozat->getId());
                $st2->execute();
            }
        }
        echo 'kész';
    }
}
