<?php

namespace Controllers;

use Entities\Eppjelszo;
use mkwhelpers\FilterDescriptor;

/**
 * A WordPress oldalak (External Page Passwords plugin) jelszavai. A jelszót a rendszer sorsolja,
 * nyersen csak a létrehozó mentés válaszában jön vissza, egyszer.
 */
class eppjelszoController extends \mkwhelpers\MattableController
{

    private const LEJARATINPUTFORMAT = 'Y-m-d\TH:i';

    /** a most létrehozott rekord nyers jelszava, a mentés válaszába */
    private $generatedJelszo;
    private $invalidLejarat = false;

    public function __construct()
    {
        $this->setEntityName(Eppjelszo::class);
        $this->setKarbFormTplName('eppjelszokarbform.tpl');
        $this->setKarbTplName('eppjelszokarb.tpl');
        $this->setListBodyRowTplName('eppjelszolista_tbody_tr.tpl');
        $this->setListBodyRowVarName('_egyed');
        parent::__construct();
    }

    /**
     * @param Eppjelszo|null $t
     */
    protected function loadVars($t, $forKarb = false)
    {
        if (!$t) {
            $t = new Eppjelszo();
            $t->setLejarat(new \DateTime('+30 days 23:59'));
            $this->getEm()->detach($t);
        }
        $x = $this->getEntityFieldsArray($t);
        $x['lejaratinput'] = $t->getLejarat()?->format(self::LEJARATINPUTFORMAT);
        $x['lejaratstr'] = $t->getLejarat()?->format('Y.m.d. H:i');
        $x['createdstr'] = $t->getCreatedStr();
        $x['createdbynev'] = $t->getCreatedbyNev();
        $x['visszavonva'] = $t->isVisszavonva();
        $x['visszavonvaonstr'] = $t->getVisszavonvaonStr();
        $x['visszavonvabynev'] = $t->getVisszavonvabyNev();
        $foglalas = $t->getIdopontfoglalas();
        $x['jelentkezes'] = $foglalas
            ? trim($foglalas->getIdopont()?->getNev() . ' ' . $foglalas->getDatumStr())
            : '';
        $x['allapot'] = match (true) {
            $t->isVisszavonva() => t('visszavonva'),
            $t->getLejarat() && $t->isLejart() => t('lejárt'),
            default => t('aktív'),
        };
        return $x;
    }

    /**
     * Kézzel, nem setEntityFieldsFromRequest()-tel: egy összerakott kérés se írhassa az azonosítót, a hash-t
     * vagy a visszavonás idejét.
     *
     * @param Eppjelszo $obj
     */
    protected function setFields($obj, $parancs = null)
    {
        if (!$obj->getId()) {
            $obj->setOldalid($this->params->getIntRequestParam('oldalid'));
            $this->generatedJelszo = $obj->generateJelszo();
        }
        $obj->setMegjegyzes($this->params->getStringRequestParam('megjegyzes'));
        $obj->setNev($this->params->getStringRequestParam('nev'));
        $obj->setEmail($this->params->getStringRequestParam('email'));
        $lejarat = \DateTime::createFromFormat(self::LEJARATINPUTFORMAT, $this->params->getStringRequestParam('lejarat'));
        if ($lejarat) {
            $obj->setLejarat($lejarat);
        } else {
            $this->invalidLejarat = true;
        }
        if ($this->params->getBoolRequestParam('visszavon')) {
            $obj->revoke(\mkw\store::getLoggedInDolgozo());
        }
        return $obj;
    }

    /**
     * @param Eppjelszo $obj
     */
    protected function validate($obj, $parancs)
    {
        $errors = [];
        if ($obj->getOldalid() <= 0) {
            $errors['oldalid'] = t('Add meg a WordPress oldal azonosítóját (post ID).');
        }
        if ($this->invalidLejarat) {
            $errors['lejarat'] = t('Add meg a lejárat dátumát és idejét.');
        } elseif (!$obj->getId() && $obj->isLejart()) {
            $errors['lejarat'] = t('A lejárat a jövőben legyen.');
        }
        return $errors;
    }

    protected function getListBodyRow($obj, $oper)
    {
        $result = parent::getListBodyRow($obj, $oper);
        if ($this->generatedJelszo !== null) {
            $result['jelszo'] = $this->generatedJelszo;
        }
        return $result;
    }

    public function getlistbody()
    {
        $view = $this->createView('eppjelszolista_tbody.tpl');

        $filter = new FilterDescriptor();
        $oldalid = $this->params->getIntRequestParam('oldalidfilter');
        if ($oldalid) {
            $filter->addFilter('oldalid', '=', $oldalid);
        }
        $szoveg = $this->params->getStringRequestParam('szovegfilter');
        if ($szoveg !== '') {
            $filter->addFilter(['nev', 'email', 'megjegyzes'], 'LIKE', '%' . $szoveg . '%');
        }
        $most = date('Y-m-d H:i:s');
        switch ($this->params->getStringRequestParam('allapotfilter')) {
            case 'aktiv':
                $filter->addSql('_xx.visszavonvaon IS NULL');
                $filter->addFilter('lejarat', '>', $most);
                break;
            case 'lejart':
                $filter->addSql('_xx.visszavonvaon IS NULL');
                $filter->addFilter('lejarat', '<=', $most);
                break;
            case 'visszavonva':
                $filter->addSql('_xx.visszavonvaon IS NOT NULL');
                break;
        }

        $this->initPager($this->getRepo()->getCount($filter));

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
        $view = $this->createView('eppjelszolista.tpl');
        $view->setVar('pagetitle', t('WordPress oldal jelszavak'));
        $view->setVar('orderselect', $this->getRepo()->getOrdersForTpl());
        $view->setVar('batchesselect', $this->getRepo()->getBatchesForTpl());
        $view->printTemplateResult();
    }

    protected function _getkarb($tplname)
    {
        $view = $this->createView($tplname);
        $view->setVar('pagetitle', t('WordPress oldal jelszó'));
        $view->setVar('oper', $this->params->getRequestParam('oper', ''));
        $view->setVar('egyed', $this->loadVars($this->getRepo()->find($this->params->getRequestParam('id', 0)), true));
        return $view->getTemplateResult();
    }

}
