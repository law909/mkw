<?php
namespace mkwhelpers;

define(__NAMESPACE__.'\URLCommand','com');
define(__NAMESPACE__.'\URLCommandSeparator','/');
define(__NAMESPACE__.'\defaultNamespace','Controllers\\');
define(__NAMESPACE__.'\controllerPostfix','Controller');

abstract class Controller {

	private $templateFactory;
	protected $generalDataLoader;
    /**
     *
     * @var ParameterHandler
     */
	protected $params;
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

	public function __construct($params) {
		$this->setTemplateFactory(\mkw\Store::getTemplateFactory());
		$this->generalDataLoader = \mkw\Store::getGdl();
		$this->params = $params;
        $this->em = \mkw\Store::getEm();
        if ($this->entityName) {
            $this->repo = $this->em->getRepository($this->entityName);
        }
	}

    public function getEntityName() {
        return $this->entityName;
    }

    public function setEntityName($ename) {
        $this->entityName = $ename;
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
	public function getEm() {
		return $this->em;
	}

	public function getRepo($entityname = null) {
        if (!$entityname) {
            return $this->repo;
        }
        return $this->em->getRepository($entityname);
	}

    protected function setTemplateFactory($path) {
		$this->templateFactory=$path;
	}

	protected function getTemplateFactory() {
		return $this->templateFactory;
	}

	public function createView($tplfilename) {
		$view=$this->getTemplateFactory()->createView($tplfilename);
		$this->generalDataLoader->loadData($view);
		return $view;
	}
}