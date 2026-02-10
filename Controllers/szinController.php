<?php

namespace Controllers;

use Entities\Szin;

class szinController extends \mkwhelpers\MattableController
{

    public function __construct($params)
    {
        $this->setEntityName(Szin::class);
        $this->setKarbFormTplName('szinkarbform.tpl');
        $this->setKarbTplName('szinkarb.tpl');
        $this->setListBodyRowTplName('szinlista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_szin');
        parent::__construct($params);
    }

    public function loadVars($t, $forKarb = false)
    {
        $x = [];
        if (!$t) {
            $t = new \Entities\Szin();
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
     * @param \Entities\Szin $obj
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
        $view = $this->createView('szinlista_tbody.tpl');

        $filter = new \mkwhelpers\FilterDescriptor();

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

        echo json_encode($this->loadDataToView($egyedek, 'szinlista', $view));
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
        $view = $this->createView('szinlista.tpl');
        $view->setVar('pagetitle', t('Színek'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $id = $this->params->getRequestParam('id', 0);
        $oper = $this->params->getRequestParam('oper', '');
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('Szín'));
        $view->setVar('oper', $oper);

        $szin = $this->getRepo()->findWithJoins($id);

        $view->setVar('egyed', $this->loadVars($szin, true));

        $view->printTemplateResult();
    }

    public function fillValues()
    {
        $this->getEm()->getConnection()->beginTransaction();

        try {
            $sql = 'SELECT DISTINCT(ertek1) FROM termekvaltozat WHERE adattipus1_id=1 ORDER BY ertek1';
            $stmt = $this->getEm()->getConnection()->prepare($sql);
            $result = $stmt->executeQuery();

            $sorrend = 100;
            while ($row = $result->fetchAssociative()) {
                $ertekNev = $row['ertek1'];
                if (empty($ertekNev)) {
                    continue;
                }

                $exists = $this->getRepo()->findBy(['nev' => $ertekNev]);

                if (empty($exists)) {
                    $szin = new \Entities\Szin();
                    $szin->setNev($ertekNev);
                    $szin->setSorrend($sorrend);
                    $this->getEm()->persist($szin);
                    $sorrend += 100;
                }
            }

            $this->getEm()->flush();

            $updateSql = 'UPDATE termekvaltozat tv 
                      INNER JOIN szin s ON tv.ertek1 = s.nev 
                      SET tv.szin_id = s.id 
                      WHERE tv.adattipus1_id = 1';
            $updateStmt = $this->getEm()->getConnection()->prepare($updateSql);
            $updateStmt->executeQuery();

            $sql = 'SELECT DISTINCT(ertek2) FROM termekvaltozat WHERE adattipus2_id=2 ORDER BY ertek2';
            $stmt = $this->getEm()->getConnection()->prepare($sql);
            $result = $stmt->executeQuery();

            $sorrend = 100;
            while ($row = $result->fetchAssociative()) {
                $ertekNev = $row['ertek2'];
                if (empty($ertekNev)) {
                    continue;
                }

                $exists = $this->getRepo(\Entities\Meret::class)->findBy(['nev' => $ertekNev]);

                if (empty($exists)) {
                    $szin = new \Entities\Meret();
                    $szin->setNev($ertekNev);
                    $szin->setSorrend($sorrend);
                    $this->getEm()->persist($szin);
                    $sorrend += 100;
                }
            }

            $this->getEm()->flush();

            $updateSql = 'UPDATE termekvaltozat tv 
                      INNER JOIN meret s ON tv.ertek2 = s.nev 
                      SET tv.meret_id = s.id 
                      WHERE tv.adattipus2_id = 2';
            $updateStmt = $this->getEm()->getConnection()->prepare($updateSql);
            $updateStmt->executeQuery();

            $this->getEm()->getConnection()->commit();
            echo 'Done.';
        } catch (\Exception $e) {
            \mkw\store::writelog('Error: ' . $e->getMessage());
            $this->getEm()->getConnection()->rollBack();
            echo 'Error: ' . $e->getMessage();
        }
    }

}