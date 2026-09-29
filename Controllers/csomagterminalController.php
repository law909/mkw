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

    public function viewGLSDownload()
    {
        $view = $this->createView('glscsomagpontletoltes.tpl');
        $view->setVar('pagetitle', t('GLS csomagpont letöltés'));
        $view->setVar('vanurl', trim((string)\mkw\store::getParameter(consts::GLSTerminalURL)) !== '');
        $view->setVar('stat', (new GLSService())->getTerminalStat());
        $view->printTemplateResult();
    }

    public function downloadGLSTerminalList()
    {
        // több ezer pont egyenkénti mentése
        @set_time_limit(600);
        $glsservice = new GLSService();
        try {
            $eredmeny = $glsservice->downloadGLSTerminalList();
        } catch (\Exception $e) {
            $this->jsonFail($e->getMessage());
            return;
        }
        echo json_encode(['ok' => true] + $eredmeny + $glsservice->getTerminalStat());
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

    /** A bizonylat karb csomagpont választójának kezdő állapota: a városok, és a kiválasztott pont városának pontjai. */
    public function getBizonylatSelectData($selid, $tipus): array
    {
        $ret = ['csoportlist' => [], 'terminallist' => []];
        if (!$tipus) {
            return $ret;
        }
        $sel = $selid ? $this->getRepo()->find($selid) : null;
        if ($sel && $sel->getTipus() !== $tipus) {
            $sel = null;
        }
        $csoport = $sel ? (string)$sel->getCsoport() : '';
        $ret['csoportlist'] = $this->getCsoportList($tipus, $csoport);
        if ($sel) {
            $ret['terminallist'] = $this->getTerminalList($tipus, $csoport, $sel);
        }
        return $ret;
    }

    private function getCsoportList($tipus, $selcsoport): array
    {
        // a DISTINCT a kolláció szerint vonja össze a "Szeged"-et és a "SZEGED"-et, a kulcs ehhez igazodik
        $key = fn($csoport) => mb_strtolower($csoport, 'UTF-8');
        $csoportok = [];
        foreach ($this->getRepo()->getCsoportok($tipus) ?? [] as $sor) {
            $csoportok[$key($sor['csoport'])] = $sor['csoport'];
        }
        // a kiválasztott pont azóta inaktív lehet, a városa akkor is kell
        if ($selcsoport !== '' && !isset($csoportok[$key($selcsoport)])) {
            $csoportok[$key($selcsoport)] = $selcsoport;
            ksort($csoportok);
        }
        $res = [];
        foreach ($csoportok as $k => $csoport) {
            $res[] = ['id' => $csoport, 'caption' => $csoport, 'selected' => $selcsoport !== '' && $k === $key($selcsoport)];
        }
        return $res;
    }

    private function getTerminalList($tipus, $csoport, ?CsomagTerminal $sel = null): array
    {
        $rec = $this->getRepo()->getByCsoport($csoport, $tipus, ['nev' => 'ASC']) ?? [];
        if ($sel && !in_array($sel, $rec, true)) {
            $rec[] = $sel;
        }
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev() . ' – ' . $sor->getCim() . ($sor->getInaktiv() ? ' (' . t('inaktív') . ')' : ''),
                'selected' => $sel && $sor->getId() === $sel->getId(),
            ];
        }
        return $res;
    }

    private function getTerminaltipusParam()
    {
        $szm = $this->getRepo(Szallitasimod::class)->find($this->params->getIntRequestParam('szmid'));
        return $szm ? $szm->getTerminaltipus() : null;
    }

    public function getAdminCsoportList()
    {
        $tipus = $this->getTerminaltipusParam();
        echo json_encode(['csoportlist' => $tipus ? $this->getCsoportList($tipus, '') : []]);
    }

    public function getAdminTerminalList()
    {
        $tipus = $this->getTerminaltipusParam();
        $csoport = $this->params->getStringRequestParam('cs');
        echo json_encode(['terminallist' => $tipus && $csoport !== '' ? $this->getTerminalList($tipus, $csoport) : []]);
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
