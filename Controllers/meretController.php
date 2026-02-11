<?php

namespace Controllers;

use Entities\Meret;
use Entities\TermekValtozat;
use mkwhelpers\FilterDescriptor;
use mkwhelpers\MattableController;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class meretController extends MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Meret::class);
        $this->setKarbFormTplName('meretkarbform.tpl');
        $this->setKarbTplName('meretkarb.tpl');
        $this->setListBodyRowTplName('meretlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_meret');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Meret();
            $this->getEm()->detach($t);
        }
        $x['id'] = $t->getId();
        $x['nev'] = $t->getNev();
        $x['sorrend'] = $t->getSorrend();

        $x['kepurl'] = $t->getKepurl();
        $x['kepurlsmall'] = $t->getKepurlSmall();
        $x['kepurlmedium'] = $t->getKepurlMedium();
        $x['kepurllarge'] = $t->getKepurlLarge();
        $x['kepleiras'] = $t->getKepleiras();
        return $x;
    }

    /**
     * @param \Entities\Meret $obj
     *
     * @return mixed
     */
    protected function setFields($obj)
    {
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setSorrend($this->params->getIntRequestParam('sorrend'));
        $obj->setKepurl($this->params->getStringRequestParam('kepurl', ''));
        $obj->setKepleiras($this->params->getStringRequestParam('kepleiras', ''));
        return $obj;
    }

    public function getlistbody()
    {
        $view = $this->createView('meretlista_tbody.tpl');

        $filter = new FilterDescriptor();
        if (!is_null($this->params->getRequestParam('nevfilter', null))) {
            $filter->addFilter(['nev'],
                'LIKE',
                '%' . $this->params->getStringRequestParam('nevfilter') . '%');
        }
        $this->initPager($this->getRepo()->getCount($filter));
        $egyedek = $this->getRepo()->getAll(
            $filter,
            $this->getOrderArray(),
            $this->getPager()->getOffset(),
            $this->getPager()->getElemPerPage()
        );

        echo json_encode($this->loadDataToView($egyedek, 'meretlista', $view));
    }

    public function getSelectList($selid = null)
    {
        $rec = $this->getRepo()->getAll();
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
        $rec = $this->getRepo()->getAll();
        $ret = '<select>';
        foreach ($rec as $sor) {
            $ret .= '<option value="' . $sor->getId() . '">' . $sor->getNev() . '</option>';
        }
        $ret .= '</select>';
        echo $ret;
    }

    public function viewlist()
    {
        $view = $this->createView('meretlista.tpl');
        $view->setVar('pagetitle', t('Méretek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Méret'));
        $view->setVar('oper', $oper);

        $meret = $this->getRepo()->find($id);

        $view->setVar('egyed', $this->loadVars($meret, true));
        $view->printTemplateResult();
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Név');
        $sheet->setCellValue('C1', 'Sorrend');
        $sheet->setCellValue('D1', 'Új név');
        $sheet->setCellValue('E1', 'Csere méret ID');

        $meretek = $this->getRepo()->getAll([], ['sorrend' => 'ASC', 'nev' => 'ASC']);
        $row = 2;
        foreach ($meretek as $meret) {
            $sheet->setCellValue('A' . $row, $meret->getId());
            $sheet->setCellValue('B' . $row, $meret->getNev());
            $sheet->setCellValue('C' . $row, $meret->getSorrend());
            $sheet->setCellValue('D' . $row, '');
            $sheet->setCellValue('E' . $row, '');
            $row++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="meretek_' . date('Y-m-d_H-i-s') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function importExcel()
    {
        $this->getEm()->getConnection()->beginTransaction();

        try {
            $file = $_FILES['file']['tmp_name'] ?? null;
            if (!$file) {
                echo json_encode(['success' => false, 'message' => 'Nincs fájl feltöltve']);
                return;
            }

            $reader = new Xlsx();
            $spreadsheet = $reader->load($file);
            $sheet = $spreadsheet->getActiveSheet();

            $highestRow = $sheet->getHighestRow();
            $torlendoMeretek = [];

            for ($row = 2; $row <= $highestRow; $row++) {
                $id = $sheet->getCell('A' . $row)->getValue();
                $ujSorrend = $sheet->getCell('C' . $row)->getValue();
                $ujNev = $sheet->getCell('D' . $row)->getValue();
                $csereMeretId = $sheet->getCell('E' . $row)->getValue();

                if (!$id) {
                    continue;
                }

                $meret = $this->getRepo()->find($id);
                if (!$meret) {
                    continue;
                }

                // Ha van csere méret ID
                if (!empty($csereMeretId)) {
                    $csereMeret = $this->getRepo()->find($csereMeretId);

                    if ($csereMeret) {
                        $regiNev = $meret->getNev();
                        $ujMeretNev = $csereMeret->getNev();

                        $updateSql = 'UPDATE termekvaltozat 
                                    SET ertek2 = :ujMeretNev, meret_id = :csereMeretId 
                                    WHERE ertek2 = :regiNev AND adattipus2_id = 2';
                        $stmt = $this->getEm()->getConnection()->prepare($updateSql);
                        $stmt->executeQuery([
                            'ujMeretNev' => $ujMeretNev,
                            'csereMeretId' => $csereMeretId,
                            'regiNev' => $regiNev
                        ]);

                        $this->getEm()->remove($meret);
                    }
                } else {
                    if (!empty($ujSorrend)) {
                        $meret->setSorrend((int)$ujSorrend);
                    }
                    if (!empty($ujNev)) {
                        $regiNev = $meret->getNev();
                        $meret->setNev($ujNev);

                        $updateSql = 'UPDATE termekvaltozat SET ertek2 = :ujNev WHERE ertek2 = :regiNev AND adattipus2_id = 2';
                        $stmt = $this->getEm()->getConnection()->prepare($updateSql);
                        $stmt->executeQuery(['ujNev' => $ujNev, 'regiNev' => $regiNev]);
                    }
                    $this->getEm()->persist($meret);
                }
            }

            $this->getEm()->flush();
            $this->getEm()->getConnection()->commit();

            echo json_encode(['success' => true, 'message' => 'Import sikeres']);
        } catch (\Exception $e) {
            \mkw\store::writelog('Excel import error: ' . $e->getMessage());
            $this->getEm()->getConnection()->rollBack();
            echo 'Hiba történt az Excel importálás során: ' . $e->getMessage();
        }
    }


}