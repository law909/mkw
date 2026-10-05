<?php

namespace Controllers;

use Entities\ValtozatbolTermekNaplo;

/**
 * A "Termék a változatból" művelet naplója (Services\TermekValtozatToTermekService írja). Csak nézni lehet:
 * auditnapló, se felvinni, se szerkeszteni, se törölni nem lehet, ezért mentés útvonala sincs.
 */
class valtozatboltermeknaploController extends \mkwhelpers\MattableController
{

    private const MENUURL = '/admin/valtozatboltermeknaplo/viewlist';
    private const JOG = 90;

    public function __construct()
    {
        $this->setEntityName(ValtozatbolTermekNaplo::class);
        $this->setKarbFormTplName('valtozatboltermeknaplokarbform.tpl');
        $this->setKarbTplName('valtozatboltermeknaplokarb.tpl');
        $this->setListBodyRowTplName('valtozatboltermeknaplolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new ValtozatbolTermekNaplo();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['createdstr'] = $t->getCreatedStr();
        $x['termekid'] = $t->getTermek()?->getId();
        $x['termeknev'] = $t->getTermek()?->getNev();
        $x['ujtermekid'] = $t->getUjtermek()?->getId();
        $x['ujtermeknev'] = $t->getUjtermek()?->getNev();
        $termekjson = json_decode((string)$t->getTermekjson(), true) ?: [];
        $ujtermekjson = json_decode((string)$t->getUjtermekjson(), true) ?: [];
        // a termék törlése után is látszódjon, mi volt a neve
        $x['termeknev'] ??= $termekjson['nev'] ?? '';
        $x['ujtermeknev'] ??= $ujtermekjson['nev'] ?? '';
        $atiras = json_decode((string)$t->getAtirasjson(), true) ?: [];
        $x['bizonylattetel'] = count($atiras['bizonylattetel'] ?? []);
        if ($forKarb) {
            $x['atiras'] = [];
            foreach ($atiras as $tabla => $idk) {
                $x['atiras'][] = ['tabla' => $tabla, 'db' => count($idk), 'idk' => implode(', ', $idk)];
            }
            foreach (['termekjson', 'valtozatjson', 'ujtermekjson'] as $mezo) {
                $adat = json_decode((string)$t->{'get' . ucfirst($mezo)}(), true);
                $x[$mezo] = $adat === null ? (string)$t->{'get' . ucfirst($mezo)}()
                    : json_encode($adat, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        return $x;
    }

    protected function setFields($obj)
    {
        throw new \RuntimeException('A napló nem szerkeszthető.');
    }

    private function checkJog(): bool
    {
        if (\mkw\store::haveMenuJog(self::MENUURL, self::JOG)) {
            return true;
        }
        header('HTTP/1.1 403 Forbidden');
        return false;
    }

    public function getlistbody()
    {
        if (!$this->checkJog()) {
            return;
        }
        $view = $this->createView('valtozatboltermeknaplolista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter(
                ['_xx.valtozatnev', '_xx.createdbynev', 't.nev', 't.cikkszam', 'u.nev', 'u.cikkszam'],
                'LIKE',
                '%' . $this->params->getStringRequestParam('nevfilter') . '%'
            );
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

    public function viewlist()
    {
        if (!$this->checkJog()) {
            return;
        }
        $view = $this->createView('valtozatboltermeknaplolista.tpl');

        $view->setVar('pagetitle', t('Változatból termék napló'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        if (!$this->checkJog()) {
            return '';
        }
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Változatból termék napló'));
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($this->params->getIntRequestParam('id')), true));
        return $view->getTemplateResult();
    }
}
