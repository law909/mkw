<?php

namespace mkwhelpers;

class MattableController extends Controller
{

    protected $operationName = 'oper';
    protected $idName = 'id';
    protected $addOperation = 'add';
    protected $addreopenOperation = 'addreopen';
    protected $editOperation = 'edit';
    protected $delOperation = 'del';
    protected $inheritOperation = 'inherit';
    protected $stornoOperation = 'storno';
    private $pager;
    private $listBodyRowTplName;
    private $listBodyRowVarName;
    private $karbFormTplName;
    private $karbTplName;
    private $pagetitle;
    private $pluralpagetitle;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplateFactory(\mkw\store::getTemplateFactory());
    }

    public function setPageTitle($val)
    {
        $this->pagetitle = $val;
    }

    public function getPageTitle()
    {
        return $this->pagetitle;
    }

    public function setPluralPageTitle($val)
    {
        $this->pluralpagetitle = $val;
    }

    public function getPluralPageTitle()
    {
        return $this->pluralpagetitle;
    }

    protected function getPager()
    {
        return $this->pager;
    }

    public function getListBodyRowTplName()
    {
        return $this->listBodyRowTplName;
    }

    public function setListBodyRowTplName($name)
    {
        $this->listBodyRowTplName = $name;
    }

    public function getListBodyRowVarName()
    {
        return $this->listBodyRowVarName;
    }

    public function setListBodyRowVarName($name)
    {
        $this->listBodyRowVarName = $name;
    }

    public function getKarbFormTplName()
    {
        return $this->karbFormTplName;
    }

    public function setKarbFormTplName($name)
    {
        $this->karbFormTplName = $name;
    }

    public function getKarbTplName()
    {
        return $this->karbTplName;
    }

    public function setKarbTplName($name)
    {
        $this->karbTplName = $name;
    }

    protected function setVars($view)
    {
    }

    /**
     * A törzshöz kapcsolt dokumentumok linkjei a lista dokumentum-oszlopához (dokumentumlinkek.tpl).
     *
     * @param iterable<\Entities\Dokumentumtar> $dokok
     */
    protected function getDokLinkek($dokok)
    {
        $ret = [];
        foreach ($dokok as $dok) {
            $ret[] = $dok->toLinkArray();
        }
        return $ret;
    }

    protected function beforeRemove($o)
    {
    }

    protected function afterSave($o, $parancs = null)
    {
    }

    /**
     * Csak olvasható-e a rekord: a karb szerkeszthetetlenül töltődik be, és a mentés is elutasítja.
     * Alapból minden szerkeszthető, a leszármazott dönthet másképp (lásd bizonylatfejController).
     *
     * Csak a MEGLÉVŐ rekord módosítására (edit, del) vonatkozik – a belőle képzett vagy stornó
     * bizonylat új rekord, azt nem korlátozza.
     *
     * @param object|null $record
     *
     * @return bool
     */
    protected function isReadonly($record)
    {
        return false;
    }

    /**
     * A törzs saját ellenőrzései mentés előtt. Üres tömb = rendben, egyébként a
     * felhasználónak szóló hibaüzenetek.
     *
     * Itt még nyitva van az EntityManager, tehát szabad lekérdezni – flush közben elszállt
     * kivétel után már nem lehet (a Doctrine lezárja), ezért ami előre ellenőrizhető, azt
     * érdemes ide tenni, és nem az adatbázis hibájából visszafejteni.
     *
     * A szöveges kulcs mezőnévnek számít (a formon szereplő `name`): a kliens az ilyen mezőt
     * megjelöli és odaírja az üzenetet. A számmal indexelt elemek általános üzenetek.
     *
     *     return ['charkod' => t('Ez a charkód már foglalt.')];
     *
     * @param object $obj a már kitöltött entitás
     * @param string $parancs add / edit / …
     *
     * @return array
     */
    protected function validate($obj, $parancs)
    {
        return [];
    }

    private function checkValid($obj, $parancs)
    {
        $errors = $this->validate($obj, $parancs);
        if (!$errors) {
            return;
        }
        $fields = [];
        foreach ($errors as $key => $message) {
            if (is_string($key)) {
                $fields[$key] = $message;
            }
        }
        throw new \mkwhelpers\Exceptions\UserMessageException(implode(' ', $errors), $fields);
    }

    /** A setFields() a legtöbb helyen visszaadja az entitást, de nem mindenhol. */
    private function fillFields($obj, $parancs)
    {
        $filled = $this->setFields($obj, $parancs);
        return is_object($filled) ? $filled : $obj;
    }

    protected function saveData()
    {
        $obj = null;
        $parancs = $this->params->getRequestParam($this->operationName, '');
        $id = $this->params->getRequestParam($this->idName, 0);
        switch ($parancs) {
            case $this->addOperation:
            case $this->addreopenOperation:
            case $this->inheritOperation:
            case $this->stornoOperation:
                $cl = $this->getEntityName();
                $obj = $this->fillFields(new $cl(), $parancs);
                $this->checkValid($obj, $parancs);
                $this->getEm()->persist($obj);
                $this->getEm()->flush();
                $this->afterSave($obj, $parancs);
                break;
            case $this->editOperation:
                $obj = $this->getRepo()->find($id);
                if (!$obj) {
                    throw new \mkwhelpers\Exceptions\UserMessageException(t('A rekord nem található.'));
                }
                // a form csak olvasható állapotban is beküldhető kézzel összerakott kéréssel
                if ($this->isReadonly($obj)) {
                    throw new \mkwhelpers\Exceptions\UserMessageException(t('A rekord nem módosítható.'));
                }
                $obj = $this->fillFields($obj, $parancs);
                $this->checkValid($obj, $parancs);
                $this->getEm()->persist($obj);
                $this->getEm()->flush();
                $this->afterSave($obj, $parancs);
                break;
            case $this->delOperation:
                $obj = $this->getRepo()->find($id);
                if ($obj) {
                    if ($this->isReadonly($obj)) {
                        throw new \mkwhelpers\Exceptions\UserMessageException(t('A rekord nem törölhető.'));
                    }
                    $this->beforeRemove($obj);
                    $this->getEm()->remove($obj);
                    $this->getEm()->flush();
                    $this->afterSave($obj, $parancs);
                }
                break;
        }
        return ['id' => $id, 'obj' => $obj, 'operation' => $parancs];
    }

    public function save()
    {
        try {
            $ret = $this->saveData();
            switch ($ret['operation']) {
                case $this->addOperation:
                case $this->addreopenOperation:
                case $this->editOperation:
                case $this->inheritOperation:
                    echo json_encode($this->getListBodyRow($ret['obj'], $ret['operation']));
                    break;
                case $this->stornoOperation:
                    break;
                case $this->delOperation:
                    echo $ret['id'];
            }
        } catch (\Throwable $ex) {
            $error = \mkwhelpers\ErrorMessage::toUserMessage($ex);
            $this->jsonError($error['message'], $error['status'], $error['fields']);
        }
    }

    protected function getOrderArray()
    {
        //TODO SQLINJECTION
        return $this->getRepo()->getOrder($this->params->getRequestParam('order', 1));
    }

    protected function initPager($elemcount, $elemperpage = null, $pageno = null)
    {
        if (!$elemperpage) {
            $elemperpage = $this->params->getIntRequestParam('elemperpage', \mkw\store::getParameter(\mkw\consts::Termeklistatermekdb, 30));
        }
        if (!$pageno) {
            $pageno = $this->params->getIntRequestParam('pageno', 1);
        }
        $this->pager = new PagerCalc($elemcount, $elemperpage, $pageno);
    }

    protected function loadPagerValues($ide)
    {
        if ($this->pager) {
            return $this->pager->loadValues($ide);
        }
        return $ide;
    }

    protected function loadDataToView($data, $datavarname = '', $view = null)
    {
        $vl = [];
        foreach ($data as $t) {
            $vl[] = $this->loadVars($t);
        }
        $view->setVar($datavarname, $vl);
        $result = [];
        $result['html'] = $view->getTemplateResult();
        $result = $this->loadPagerValues($result);
        return $result;
    }

    protected function getListBodyRow($obj, $oper)
    {
        $view = $this->createView($this->listBodyRowTplName);
        $this->setVars($view);
        $vl = $this->loadVars($obj);
        $view->setVar($this->listBodyRowVarName, $vl);
        $result = [];
        if (is_object($obj)) {
            $result['id'] = $obj->getId();
        } else {
            $result['id'] = $obj['id'];
        }
        $result['oper'] = $oper;
        $result['html'] = $view->getTemplateResult();
        return $result;
    }

    public function getkarb()
    {
        echo $this->_getkarb($this->karbFormTplName);
    }

    public function viewkarb()
    {
        echo $this->_getkarb($this->karbTplName);
    }

}
