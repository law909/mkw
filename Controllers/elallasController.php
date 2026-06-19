<?php

namespace Controllers;

use Entities\Elallas;
use mkw\store;
use Traits\GetsFieldValue;

class elallasController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Elallas::class);
        $this->setKarbFormTplName('elallaskarbform.tpl');
        $this->setKarbTplName('elallaskarb.tpl');
        $this->setListBodyRowTplName('elallaslista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    protected function loadVars($t)
    {
        if (!$t) {
            $t = new \Entities\Elallas();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['created'] = $t->getCreatedStr();
        $x['lastmod'] = $t->getLastmodStr();
        return $x;
    }

    protected function loadNaplok($t)
    {
        $naploCtrl = new elallasnaploController();
        $x = [];
        if ($t) {
            foreach ($t->getNaplok() as $n) {
                $x[] = $naploCtrl->loadVars($n);
            }
        }
        return $x;
    }

    protected function setFields($obj)
    {
        $this->setEntityFieldsFromRequest($obj);
        $naploids = $this->params->getArrayRequestParam('naploid');
        foreach ($naploids as $naploid) {
            $kuldo = $this->params->getStringRequestParam('naplokuldo_' . $naploid, '');
            $fogado = $this->params->getStringRequestParam('naplofogado_' . $naploid, '');
            $szoveg = $this->params->getStringRequestParam('naploszoveg_' . $naploid, '');
            $megjegyzes = $this->params->getStringRequestParam('naplomegjegyzes_' . $naploid, '');
            $esemenyido = $this->params->getStringRequestParam('naploesemenyido_' . $naploid, '');
            $irany = $this->params->getIntRequestParam('naploirany_' . $naploid, 1);
            if ($kuldo === '' && $fogado === '' && $szoveg === '' && $megjegyzes === '' && $esemenyido === '') {
                continue;
            }
            $oper = $this->params->getStringRequestParam('naplooper_' . $naploid);
            if ($oper == 'add') {
                $naplo = new \Entities\Elallasnaplo();
                $obj->addNaplo($naplo);
                $naplo->setKuldo($kuldo);
                $naplo->setFogado($fogado);
                $naplo->setSzoveg($szoveg);
                $naplo->setMegjegyzes($megjegyzes);
                $naplo->setEsemenyido($esemenyido);
                $naplo->setIrany($irany);
                $this->getEm()->persist($naplo);
            } elseif ($oper == 'edit') {
                /** @var \Entities\Elallasnaplo $naplo */
                $naplo = \mkw\store::getEm()->getRepository(\Entities\Elallasnaplo::class)->find($naploid);
                if ($naplo) {
                    $naplo->setKuldo($kuldo);
                    $naplo->setFogado($fogado);
                    $naplo->setSzoveg($szoveg);
                    $naplo->setMegjegyzes($megjegyzes);
                    $naplo->setEsemenyido($esemenyido);
                    $naplo->setIrany($irany);
                    $this->getEm()->persist($naplo);
                }
            }
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('elallaslista_tbody.tpl');

        $filter = [];
        if (!is_null($this->params->getRequestParam('idfilter', null))) {
            $filter['fields'][] = ['nev', 'email', 'bizonylat'];
            $filter['values'][] = $this->params->getStringRequestParam('idfilter');
        }

        $this->initPager(
            $this->getRepo()->getCount($filter),
            $this->params->getIntRequestParam('elemperpage', 30),
            $this->params->getIntRequestParam('pageno', 1)
        );

        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'egyedlista', $view));
    }

    public function viewselect()
    {
        $view = $this->createView('elallaslista.tpl');

        $view->setVar('pagetitle', t('Elállás a szerződéstől'));
        $view->printTemplateResult();
    }

    public function viewlist()
    {
        $view = $this->createView('elallaslista.tpl');

        $view->setVar('pagetitle', t('Elállás a szerződéstől'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Elállás a szerződéstől'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record));
        $view->setVar('naplok', $this->loadNaplok($record));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll([], ['created' => 'DESC']);
        $res = [];
        foreach ($rec as $sor) {
            $caption = trim((string)$sor->getNev());
            if ($sor->getBizonylat()) {
                $caption = ($caption !== '' ? $caption . ' - ' : '') . $sor->getBizonylat();
            }
            if ($caption === '') {
                $caption = '#' . $sor->getId();
            }
            $res[] = ['id' => $sor->getId(), 'caption' => $caption, 'selected' => ($sor->getId() == $selid)];
        }
        return $res;
    }

    public function elallasform()
    {
        $view = $this->createMainView('elallasform.tpl');
        \mkw\store::fillTemplate($view);
        $view->setVar('pagetitle', t('Elállás a szerződéstől') . ' - ' . \mkw\store::getParameter(\mkw\consts::Oldalcim));
        $view->printTemplateResult(true);
    }

    public function elallasment()
    {
        $hibas = false;
        $hibak = [];
        $nev = $this->params->getStringRequestParam('nev');
        $email = $this->params->getStringRequestParam('email');
        $bizonylat = $this->params->getStringRequestParam('bizonylat');
        $szoveg = $this->params->getStringRequestParam('szoveg');
        if ($nev === '') {
            $hibas = true;
            $hibak['nev'] = t('Adja meg a nevét');
        }
        if (!\mkw\validate::is($email, 'EmailAddress')) {
            $hibas = true;
            $hibak['email'] = t('Hibás az emailcím');
        }
        if ($bizonylat === '') {
            $hibas = true;
            $hibak['bizonylat'] = t('Adja meg a megrendelés számát');
        }
        if (!$hibas) {
            $now = new \DateTime();

            $elallas = new \Entities\Elallas();
            $elallas->setNev($nev);
            $elallas->setEmail($email);
            $elallas->setBizonylat($bizonylat);
            $elallas->setSzoveg($szoveg);

            $beNaplo = new \Entities\Elallasnaplo();
            $elallas->addNaplo($beNaplo);
            $beNaplo->setIrany(1);
            $beNaplo->setEsemenyido($now);
            $beNaplo->setKuldo(trim($nev . ' <' . $email . '>'));
            $beNaplo->setFogado(\mkw\store::getParameter(\mkw\consts::Tulajnev));
            $beNaplo->setSzoveg($szoveg);
            $beNaplo->setMegjegyzes(t('Elállási nyilatkozat beérkezett a webshop felületén keresztül.'));

            $this->getEm()->persist($elallas);
            $this->getEm()->persist($beNaplo);
            $this->getEm()->flush();

            $this->sendElismerveny($elallas, $now);

            $view = $this->createMainView('elallaskosz.tpl');
            \mkw\store::fillTemplate($view);
        } else {
            $view = $this->createMainView('elallasform.tpl');
            \mkw\store::fillTemplate($view);
            $view->setVar('nev', $nev);
            $view->setVar('email', $email);
            $view->setVar('bizonylat', $bizonylat);
            $view->setVar('szoveg', $szoveg);
            $view->setVar('hibak', $hibak);
        }
        $view->printTemplateResult(false);
    }

    protected function sendElismerveny($elallas, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        $emailtpl = $this->getEm()->getRepository(\Entities\Emailtemplate::class)
            ->find(\mkw\store::getParameter(\mkw\consts::ElallasElismervenySablon));
        if (!$emailtpl) {
            return false;
        }

        $tpldata = [
            'nev' => $elallas->getNev(),
            'email' => $elallas->getEmail(),
            'bizonylat' => $elallas->getBizonylat(),
            'szoveg' => $elallas->getSzoveg(),
            'kuldesdatum' => $now->format(\mkw\store::$DateFormat),
            'kuldesido' => $now->format(\mkw\store::$TimeFormat),
            'kuldesidopont' => $now->format(\mkw\store::$DateTimeFormat),
        ];

        $subject = \mkw\store::getTemplateFactory()->createMainView('string:' . $emailtpl->getTargy());
        $subject->setVar('elallas', $tpldata);

        $body = \mkw\store::getTemplateFactory()->createMainView(
            'string:' . str_replace('&#39;', '\'', html_entity_decode($emailtpl->getHTMLSzoveg()))
        );
        $body->setVar('elallas', $tpldata);

        $mailer = \mkw\store::getMailer();
        $mailer->setTo($elallas->getEmail());
        $mailer->setSubject($subject->getTemplateResult());
        $mailer->setMessage($body->getTemplateResult());
        $mailer->send();

        $kiNaplo = new \Entities\Elallasnaplo();
        $elallas->addNaplo($kiNaplo);
        $kiNaplo->setIrany(-1);
        $kiNaplo->setEsemenyido($now);
        $kiNaplo->setKuldo(\mkw\store::getParameter(\mkw\consts::Tulajnev));
        $kiNaplo->setFogado($elallas->getEmail());
        $kiNaplo->setSzoveg($elallas->getSzoveg());
        $kiNaplo->setMegjegyzes(t('Elállás átvételi elismervény kiküldve.'));
        $this->getEm()->persist($kiNaplo);
        $this->getEm()->flush();

        return true;
    }

}
