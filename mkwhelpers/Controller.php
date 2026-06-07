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

    public function __construct($params)
    {
        $this->setTemplateFactory(\mkw\store::getTemplateFactory());
        $this->generalDataLoader = \mkw\store::getGdl();
        $this->params = $params;
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
}