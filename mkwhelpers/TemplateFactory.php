<?php
namespace mkwhelpers;

use Doctrine\Common\Annotations\PsrCachedReader;

class TemplateFactory {
	private $path_template;
	private $path_template_c;
	private $path_smartyconfig;
	private $path_smartycache;
	private $main_path_template;
	private $main_path_smartyconfig;
	private $main_path_smartycache;
	private $pubadmin_path_template;
	private $pubadmin_path_template_default;
	private $templateenginename;
    private $path_template_default;

	public function __construct($ini) {
		$this->path_template=$ini['path.template'];
		$this->path_template_default=$ini['path.template.default'];
		$this->path_template_c=$ini['path.template_c'];
		$this->path_smartyconfig=$ini['path.smartyconfig'];
		$this->path_smartycache=$ini['path.smartycache'];
		$this->main_path_template=$ini['main.path.template'];
		$this->main_path_smartyconfig=$ini['main.path.smartyconfig'];
		$this->main_path_smartycache=$ini['main.path.smartycache'];
		if (array_key_exists('pubadmin.path.template', $ini)) {
            $this->pubadmin_path_template = $ini['pubadmin.path.template'];
        }
		else {
            $this->pubadmin_path_template = '';
        }
        if (array_key_exists('pubadmin.path.template.default', $ini)) {
            $this->pubadmin_path_template_default = $ini['pubadmin.path.template.default'];
        }
        else {
            $this->pubadmin_path_template_default = '';
        }
		$this->templateenginename=$ini['tplengine'];
	}

	public function getTemplate() {
		return $this->path_template;
	}

	public function getTemplateDefault() {
		return $this->path_template_default;
	}

	public function getTemplateC() {
		return $this->path_template_c;
	}

	public function getSmartyConfig() {
		return $this->path_smartyconfig;
	}

	public function getSmartyCache() {
		return $this->path_smartycache;
	}

	public function getMainTemplate() {
		return $this->main_path_template;
	}

	public function getMainSmartyConfig() {
		return $this->main_path_smartyconfig;
	}

	public function getMainSmartyCache() {
		return $this->main_path_smartycache;
	}

	public function getPubAdminTemplate() {
	    return $this->pubadmin_path_template;
    }

    public function getPubAdminTemplateDefault() {
        return $this->pubadmin_path_template_default;
    }

	public function getTemplateEngineName() {
		return $this->templateenginename;
	}

	public function createView($tplfilename) {
		if (strtolower($this->templateenginename)=='smarty') {
            if (file_exists($this->getTemplate() . $tplfilename)) {
                $view = new SmartyView(
                    $this->getTemplateC(),
                    $this->getTemplate(),
                    $tplfilename,
                    $this->getSmartyConfig(),
                    $this->getSmartyCache()
                );
            }
            else {
                $view = new SmartyView(
                    $this->getTemplateC(),
                    $this->getTemplateDefault(),
                    $tplfilename,
                    $this->getSmartyConfig(),
                    $this->getSmartyCache()
                );
            }
		}
		return $view;
	}

	public function createMainView($tplfilename) {
		if (strtolower($this->templateenginename)=='smarty') {
			$view = new SmartyView(
			    $this->getTemplateC(),
                $this->getMainTemplate(),
                $tplfilename,
                $this->getMainSmartyConfig(),
                $this->getMainSmartyCache()
            );
		}
		return $view;
	}

	public function createPubAdminView($tplfilename) {
	    if (strtolower($this->templateenginename) == 'smarty') {
            if (file_exists($this->getPubAdminTemplate() . $tplfilename)) {
                $view = new SmartyView(
                    $this->getTemplateC(),
                    $this->getPubAdminTemplate(),
                    $tplfilename,
                    $this->getSmartyConfig(),
                    $this->getSmartyCache()
                );
            }
            else {
                $view = new SmartyView(
                    $this->getTemplateC(),
                    $this->getPubAdminTemplateDefault(),
                    $tplfilename,
                    $this->getSmartyConfig(),
                    $this->getSmartyCache()
                );
            }
        }
        return $view;
    }

}