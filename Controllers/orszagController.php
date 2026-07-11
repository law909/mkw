<?php

namespace Controllers;

use Entities\Afa;
use Entities\Orszag;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class orszagController extends \mkwhelpers\MattableController
{

    public function __construct()
    {
        $this->setEntityName(Orszag::class);
        $this->setKarbFormTplName('orszagkarbform.tpl');
        $this->setKarbTplName('orszagkarb.tpl');
        $this->setListBodyRowTplName('orszaglista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    public function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new \Entities\Orszag();
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['valutanemnev'] = $t->getValutanemNev();
        $x['valutanemid'] = $t->getValutanemId();
        $x['afanev'] = $t->getAfaNev();
        $x['afaid'] = $t->getAfaId();
        if ($forKarb) {
            $valutanem = new valutanemController();
            $x['valutanemlist'] = $valutanem->getSelectList($t->getValutanemId());
            $afa = new afaController();
            $x['afalist'] = $afa->getSelectList($t->getAfaId());
        }
        return $x;
    }

    /**
     * @param \Entities\Orszag $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj = $this->setEntityFieldsFromRequest($obj);

        $valutanem = $this->getRepo(Valutanem::class)->find($this->params->getIntRequestParam('valutanem', 0));
        if ($valutanem) {
            $obj->setValutanem($valutanem);
        } else {
            $obj->setValutanem(null);
        }

        $afa = $this->getRepo(Afa::class)->find($this->params->getIntRequestParam('afa', 0));
        if ($afa) {
            $obj->setAfa($afa);
        } else {
            $obj->setAfa(null);
        }
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('orszaglista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nevfilter') . '%');
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
        $view = $this->createView('orszaglista.tpl');

        $view->setVar('pagetitle', t('Országok'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);

        $view->setVar('pagetitle', t('Ország'));
        $view->setVar('formaction', \mkw\store::getRouter()->generate('adminorszagsave'));
        $view->setVar('oper', $oper);
        $record = $this->getRepo()->find($id);
        $view->setVar('egyed', $this->loadVars($record, true));
        return $view->getTemplateResult();
    }

    public function getSelectList($selid = null, $mind = false)
    {
        if ($mind) {
            $rec = $this->getRepo()->getAll([], ['nev' => 'ASC']);
        } else {
            $rec = $this->getRepo()->getAllLathato();
        }
        $res = [];
        foreach ($rec as $sor) {
            $res[] = [
                'id' => $sor->getId(),
                'caption' => $sor->getNev(),
                'selected' => ($sor->getId() == $selid)
            ];
        }
        return $res;
    }

    public function htmllist()
    {
        $rec = $this->getRepo()->getAll([], ['nev' => 'asc']);
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function importExcel()
    {
        $this->getEm()->getConnection()->beginTransaction();

        try {
            if (!isset($_FILES['toimport'])) {
                throw new \Exception('Nincs feltöltött fájl vagy hiba történt a feltöltés során.');
            }

            $file = $_FILES['toimport']['tmp_name'] ?? null;
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getSheet(0);

            $valutanem = $this->getRepo(Valutanem::class)->find(2);
            $highestRow = $sheet->getHighestRow();

            for ($row = 2; $row <= $highestRow; $row++) {
                $iso3166 = trim((string)$sheet->getCell('C' . $row)->getValue());
                if ($iso3166 === '') {
                    continue;
                }
                $iso3166 = mb_strtoupper($iso3166, 'UTF-8');

                $nev = trim((string)$sheet->getCell('K' . $row)->getValue());
                if ($nev === '') {
                    continue;
                }
                $nev = mb_strtoupper($nev, 'UTF-8');

                $orszag = $this->getRepo()->findOneBy(['iso3166' => $iso3166]);
                if ($orszag) {
                    continue;
                }

                $orszag = new Orszag();
                $orszag->setIso3166($iso3166);
                $orszag->setNev($nev);
                $orszag->setValutanem($valutanem);
                $orszag->setLathato4(1);

                $this->getEm()->persist($orszag);
            }

            $this->getEm()->flush();
            $this->getEm()->getConnection()->commit();

            echo json_encode(['msg' => 'Import sikeres']);
        } catch (\Exception $e) {
            \mkw\store::writelog('Excel import error: ' . $e->getMessage());
            $this->getEm()->getConnection()->rollBack();
            echo json_encode(['msg' => 'Hiba történt az Excel importálás során: ' . $e->getMessage()]);
        }
    }

}
