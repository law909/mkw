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

    protected function beforeRemove($o)
    {
    }

    protected function afterSave($o, $parancs = null)
    {
    }

    protected function saveData()
    {
        $parancs = $this->params->getRequestParam($this->operationName, '');
        $id = $this->params->getRequestParam($this->idName, 0);
        try {
            switch ($parancs) {
                case $this->addOperation:
                case $this->addreopenOperation:
                case $this->inheritOperation:
                case $this->stornoOperation:
                    $cl = $this->getEntityName();
                    $obj = new $cl();
                    $this->getEm()->persist($this->setFields($obj, $parancs));
                    $this->getEm()->flush();
                    $this->afterSave($obj, $parancs);
                    break;
                case $this->editOperation:
                    $obj = $this->getRepo()->find($id);
                    $this->getEm()->persist($this->setFields($obj, $parancs));
                    $this->getEm()->flush();
                    $this->afterSave($obj, $parancs);
                    break;
                case $this->delOperation:
                    $obj = $this->getRepo()->find($id);
                    if ($obj) {
                        $this->beforeRemove($obj);
                        $this->getEm()->remove($obj);
                        $this->getEm()->flush();
                        $this->afterSave($obj, $parancs);
                    }
                    break;
            }
            return ['id' => $id, 'obj' => $obj, 'operation' => $parancs];
        } catch (\Exception $e) {
            throw $e;
        }
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
        } catch (Exception $ex) {
//            echo json_encode(array('error' => $ex->getMessage()));
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

    protected function getEntityFieldsArray($entity)
    {
        $result = [];
        if ($entity && is_object($entity)) {
            $meta = $this->getEm()->getClassMetadata(get_class($entity));
            foreach ($meta->getFieldNames() as $fieldName) {
                $getter = 'get' . ucfirst($fieldName);
                if (!method_exists($entity, $getter)) {
                    $getter = 'is' . ucfirst($fieldName);
                }
                if (method_exists($entity, $getter)) {
                    $result[$fieldName] = $entity->$getter();
                }
            }
        }
        return $result;
    }

    /**
     * A request paraméterek automatikus visszaírása az entity skalár mezőire a
     * Doctrine metaadat (mezőnév + típus) alapján — a getEntityFieldsArray() párja.
     * Új skalár mező felvételekor nem kell a controller setFields()-ét bővíteni:
     * ha a form ugyanazon a néven beküldi, magától beíródik.
     *
     * Csak a ténylegesen beküldött (existsRequestParam) mezőket írja, így a formon
     * nem szereplő mezőket nem nullázza ki. A típus dönti el a getter-t:
     *   string|text              -> getStringRequestParam (raw esetén getOriginalStringRequestParam)
     *   integer|smallint|bigint  -> getIntRequestParam
     *   decimal|float            -> getFloatRequestParam
     *
     * A boolean mezőket SZÁNDÉKOSAN nem kezeli: a bekapcsolatlan checkbox nem kerül
     * be a POST-ba, így a "csak a beküldöttet írd" elv kinullázás helyett a régi
     * értéket őrizné meg. A boolean mezőket (és az asszociációkat, dátumokat) a
     * controllernek kézzel kell beállítania. Alapból kihagyott rendszermezők:
     * az azonosító(k), valamint id, slug, created, updated.
     *
     * @param object $entity
     * @param array $options {
     *     'raw'  => array  HTML mezők, sanitizálás nélkül olvasva
     *     'skip' => array  további kihagyandó mezőnevek
     * }
     *
     * @return object a (módosított) entity
     */
    protected function setEntityFieldsFromRequest($entity, array $options = [])
    {
        if (!$entity || !is_object($entity)) {
            return $entity;
        }
        $raw = array_flip($options['raw'] ?? []);
        $skip = array_flip(array_merge(['id', 'slug', 'created', 'updated'], $options['skip'] ?? []));
        $meta = $this->getEm()->getClassMetadata(get_class($entity));
        foreach ($meta->getFieldNames() as $fieldName) {
            if (isset($skip[$fieldName]) || $meta->isIdentifier($fieldName)) {
                continue;
            }
            if (!$this->params->existsRequestParam($fieldName)) {
                continue;
            }
            $setter = 'set' . ucfirst($fieldName);
            if (!method_exists($entity, $setter)) {
                continue;
            }
            switch ($meta->getTypeOfField($fieldName)) {
                case 'string':
                case 'text':
                    $value = isset($raw[$fieldName])
                        ? $this->params->getOriginalStringRequestParam($fieldName)
                        : $this->params->getStringRequestParam($fieldName);
                    break;
                case 'integer':
                case 'smallint':
                case 'bigint':
                    $value = $this->params->getIntRequestParam($fieldName);
                    break;
                case 'decimal':
                case 'float':
                    $value = $this->params->getFloatRequestParam($fieldName);
                    break;
                case 'boolean':
                    $value = $this->params->getBoolRequestParam($fieldName);
                    break;
                case 'datetime':
                case 'date':
                    $value = $this->params->getStringRequestParam($fieldName);
                    break;
                default:
                    // json, stb. — nem automatikus
                    continue 2;
            }
            $entity->$setter($value);
        }
        return $entity;
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
