<?php

namespace Controllers;

use Entities\CsomagTerminal;
use Entities\Szallitasimod;
use mkw\consts;
use Services\FoxpostService;
use Services\GLSService;

class csomagterminalController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(CsomagTerminal::class);
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function downloadFoxpostTerminalList()
    {
        $foxpostsvc = new FoxpostService();
        $foxpostsvc->downloadFoxpostTerminalList();
    }

    public function downloadGLSTerminalList()
    {
        $glsservice = new GLSService();
        $glsservice->downloadGLSTerminalList();
    }

    public function getCsoportok()
    {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo(Szallitasimod::class)->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $key = 'lscsoport' . $szmid;
        $elozocsoport = \mkw\store::getMainSession()->$key;

        $rec = $this->getRepo(CsomagTerminal::class)->getCsoportok($tipus);
        $res = [];
        foreach ($rec as $sor) {
            $r = [
                'id' => $sor['csoport'],
                'caption' => $sor['csoport']
            ];
            if ($elozocsoport && ($sor['csoport'] == $elozocsoport)) {
                $r['selected'] = true;
            } else {
                $r['selected'] = false;
            }
            $res[] = $r;
        }
        $view = \mkw\store::getTemplateFactory()->createMainView('checkout' . $tipus . 'csoportlist.tpl');
        $view->setVar($tipus . 'csoportlist', $res);
        echo json_encode([
            'html' => $view->getTemplateResult()
        ]);
    }

    public function getTerminalok()
    {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo(Szallitasimod::class)->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $key = 'lsterminal' . $this->params->getStringRequestParam('cs');
        $elozoterminal = \mkw\store::getMainSession()->$key;

        $rec = $this->getRepo(CsomagTerminal::class)->getByCsoport($this->params->getStringRequestParam('cs'), $tipus, ['nev' => 'ASC']);
        $res = [];
        foreach ($rec as $sor) {
            $r = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'cim' => $sor->getCim()
            ];
            if ($elozoterminal && ($sor->getId() == $elozoterminal)) {
                $r['selected'] = true;
            } else {
                $r['selected'] = false;
            }
            $res[] = $r;
        }
        $view = \mkw\store::getTemplateFactory()->createMainView('checkout' . $tipus . 'terminallist.tpl');
        $view->setVar($tipus . 'terminallist', $res);
        echo json_encode([
            'html' => $view->getTemplateResult()
        ]);
    }

    public function getSelectList($selid, $tipus = null)
    {
        if (!is_null($tipus)) {
            $filter = new \mkwhelpers\FilterDescriptor();
            $filter->addFilter('inaktiv', '=', false);
            $filter->addFilter('tipus', '=', $tipus);
            $rec = $this->getRepo()->getAll($filter, ['csoport' => 'ASC', 'nev' => 'ASC']);
            $res = [];
            foreach ($rec as $sor) {
                $res[] = [
                    'id' => $sor->getId(),
                    'caption' => $sor->getCsoport() . ' ' . $sor->getNev() . ' ' . $sor->getCim(),
                    'selected' => ($sor->getId() == $selid)
                ];
            }
            return $res;
        }
        return null;
    }

    public function getHTMLList()
    {
        $szmid = $this->params->getIntRequestParam('szmid');
        $szm = $this->getRepo(Szallitasimod::class)->find($szmid);
        $tipus = null;

        if ($szm) {
            $tipus = $szm->getTerminaltipus();
        }

        $res = $this->getSelectList(null, $tipus);

        $view = \mkw\store::getTemplateFactory()->createView('csomagterminalselect.tpl');
        $view->setVar('terminallist', $res);
        $view->setVar('variable', $tipus . 'terminal');
        $view->setVar('tagid', 'CsomagTerminalEdit');

        echo json_encode([
            'html' => $view->getTemplateResult()
        ]);
    }

    public function getTerminalId()
    {
        $tipus = $this->params->getStringRequestParam('tipus');
        $id = $this->params->getStringRequestParam('id');
        $obj = $this->getRepo()->findBy(['tipus' => $tipus, 'idegenid' => $id]);
        if ($obj) {
            $obj = $obj[0];
        } else {
            $obj = new \Entities\CsomagTerminal();
            $obj->setTipus($tipus);
            $obj->setIdegenid($id);
            $obj->setNev($this->params->getStringRequestParam('nev'));
            $obj->setCim($this->params->getStringRequestParam('cim'));
            $obj->setCsoport($this->params->getStringRequestParam('csoport'));
            $obj->setNyitva($this->params->getStringRequestParam('nyitva'));
            $obj->setFindme($this->params->getStringRequestParam('findme'));
            $obj->setGeolat(str_replace(',', '.', $this->params->getStringRequestParam('geolat')));
            $obj->setGeolng(str_replace(',', '.', $this->params->getStringRequestParam('geolng')));
            $this->getEm()->persist($obj);
            $this->getEm()->flush();
        }
        echo json_encode(['id' => $obj->getId()]);
    }
}
