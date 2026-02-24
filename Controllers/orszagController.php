<?php

namespace Controllers;

use Entities\Orszag;
use Entities\Valutanem;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class orszagController extends \mkwhelpers\JQGridController
{

    public function __construct($params)
    {
        $this->setEntityName('Entities\Orszag');
        parent::__construct($params);
    }

    protected function loadCells($sor)
    {
        $valuta = $sor->getValutanem();
        return [
            $sor->getNev(),
            $sor->getIso3166(),
            (isset($valuta) ? $valuta->getNev() : ''),
            $sor->getLathato(),
            $sor->getLathato2(),
            $sor->getLathato3(),
            $sor->getLathato4(),
            $sor->getLathato5(),
            $sor->getLathato6(),
            $sor->getLathato7(),
            $sor->getLathato8(),
            $sor->getLathato9(),
            $sor->getLathato10(),
            $sor->getLathato11(),
            $sor->getLathato12(),
            $sor->getLathato13(),
            $sor->getLathato14(),
            $sor->getLathato15(),
        ];
    }

    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev', $obj->getNev()));
        $obj->setIso3166($this->params->getStringRequestParam('iso3166', $obj->getIso3166()));
        $obj->setLathato($this->params->getBoolRequestParam('lathato'));
        $obj->setLathato2($this->params->getBoolRequestParam('lathato2'));
        $obj->setLathato3($this->params->getBoolRequestParam('lathato3'));
        $obj->setLathato4($this->params->getBoolRequestParam('lathato4'));
        $obj->setLathato5($this->params->getBoolRequestParam('lathato5'));
        $obj->setLathato6($this->params->getBoolRequestParam('lathato6'));
        $obj->setLathato7($this->params->getBoolRequestParam('lathato7'));
        $obj->setLathato8($this->params->getBoolRequestParam('lathato8'));
        $obj->setLathato9($this->params->getBoolRequestParam('lathato9'));
        $obj->setLathato10($this->params->getBoolRequestParam('lathato10'));
        $obj->setLathato11($this->params->getBoolRequestParam('lathato11'));
        $obj->setLathato12($this->params->getBoolRequestParam('lathato12'));
        $obj->setLathato13($this->params->getBoolRequestParam('lathato13'));
        $obj->setLathato14($this->params->getBoolRequestParam('lathato14'));
        $obj->setLathato15($this->params->getBoolRequestParam('lathato15'));

        $valutanem = $this->getRepo('Entities\Valutanem')->find($this->params->getIntRequestParam('valutanem', 0));
        if ($valutanem) {
            $obj->setValutanem($valutanem);
        } else {
            $obj->setValutanem(null);
        }
        return $obj;
    }

    public function jsonlist()
    {
        $filter = new \mkwhelpers\FilterDescriptor();
        if ($this->params->getBoolRequestParam('_search', false)) {
            if (!is_null($this->params->getParam('nev', null))) {
                $filter->addFilter('nev', 'LIKE', '%' . $this->params->getStringRequestParam('nev') . '%');
            }
        }
        $rec = $this->getRepo()->getAll($filter, $this->getOrderArray());
        echo json_encode($this->loadDataToView($rec));
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
