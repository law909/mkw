<?php

namespace mkwhelpers;

define(__NAMESPACE__ . '\URLCommand', 'com');
define(__NAMESPACE__ . '\URLCommandSeparator', '/');
define(__NAMESPACE__ . '\defaultNamespace', 'Controllers\\');
define(__NAMESPACE__ . '\controllerPostfix', 'Controller');

abstract class Controller
{

    protected $generalDataLoader;
    /**
     *
     * @var ParameterHandler
     */
    protected $params;
    /**
     *
     * @var \mkwhelpers\TemplateFactory
     */
    private $templateFactory;
    /**
     *
     * @var \mkwhelpers\Repository
     */
    private $repo;
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $entityName;

    public function __construct()
    {
        $this->setTemplateFactory(\mkw\store::getTemplateFactory());
        $this->generalDataLoader = \mkw\store::getGdl();
        $this->params = \mkw\store::getInput();
        $this->em = \mkw\store::getEm();
        if ($this->entityName) {
            $this->repo = $this->em->getRepository($this->entityName);
        }
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function setEntityName($ename)
    {
        $this->entityName = $ename;
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    public function getRepo($entityname = null)
    {
        if (!$entityname) {
            return $this->repo;
        }
        return $this->em->getRepository($entityname);
    }

    public function createView($tplfilename)
    {
        $view = $this->getTemplateFactory()->createView($tplfilename);
        $this->generalDataLoader->loadData($view);
        $this->loadListParams($view, $tplfilename);
        return $view;
    }

    /**
     * A lista-sablonokhoz (…lista.tpl) átadja a "Mindig nyitva" (szűrő nyitva tartása)
     * mentett állapotát. A kulcs a lista URL-jének elérési útja (a ? előtti rész, domain nélkül) –
     * ugyanaz, amit a kliens a mentéskor használ. Így egy helyen, minden mattable listára
     * érvényesen (a controller ősosztályától függetlenül) töltődik be az érték.
     */
    protected function loadListParams($view, $tplfilename)
    {
        if (substr($tplfilename, -9) === 'lista.tpl') {
            $key = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            $view->setVar('mindignyitva', \mkw\store::getParameter($key) ? 1 : 0);
        }
    }

    public function createMainView($tplfilename)
    {
        $view = $this->getTemplateFactory()->createMainView($tplfilename);
        $this->generalDataLoader->loadData($view);
        return $view;
    }

    public function createPubAdminView($tplfilename)
    {
        $view = $this->getTemplateFactory()->createPubAdminView($tplfilename);
        $this->generalDataLoader->loadPubadminData($view);
        return $view;
    }

    /**
     *
     * @return \mkwhelpers\TemplateFactory
     */
    protected function getTemplateFactory()
    {
        return $this->templateFactory;
    }

    protected function setTemplateFactory($path)
    {
        $this->templateFactory = $path;
    }

    protected function getEntityFieldsArray($entity, $prefilled = null)
    {
        $result = [];
        if ($entity && is_object($entity)) {
            $meta = $this->getEm()->getClassMetadata(get_class($entity));
            foreach ($meta->getFieldNames() as $fieldName) {
                $getter = 'get' . \mkw\store::snakeToPascalCase($fieldName);
                if (!method_exists($entity, $getter)) {
                    $getter = 'is' . \mkw\store::snakeToPascalCase($fieldName);
                }
                if (method_exists($entity, $getter)) {
                    $result[$fieldName] = $entity->$getter();
                }
            }
        }
        if ($prefilled) {
            $result = array_merge($result, $prefilled);
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
            if (isset($skip[$fieldName]) || $meta->isIdentifier($fieldName) || $meta->hasAssociation($fieldName)) {
                continue;
            }
            if (!$this->params->existsRequestParam($fieldName) && $meta->getTypeOfField($fieldName) !== 'boolean') {
                continue;
            }
            $setter = 'set' . \mkw\store::snakeToPascalCase($fieldName);
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

}