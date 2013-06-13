<?php
namespace mkwhelpers;

class ControllerClassLoader
{
	private $_fileExtension = '.php';
	private $_namespace;
	private $_includePath;
	private $_namespaceSeparator = '\\';
	private $_controllerPostfix='Controller';

	public function __construct($ns = null, $includePath = null,$defaultController=null)
	{
		$this->_namespace = $ns;
		$this->_includePath = $includePath;
		if ($defaultController) {
			$this->_defaultController=$defaultController;
		}
	}

	public function setNamespaceSeparator($sep)
	{
		$this->_namespaceSeparator = $sep;
	}

	public function getNamespaceSeparator()
	{
		return $this->_namespaceSeparator;
	}

	public function setIncludePath($includePath)
	{
		$this->_includePath = $includePath;
	}

	public function getIncludePath()
	{
		return $this->_includePath;
	}

	public function setFileExtension($fileExtension)
	{
		$this->_fileExtension = $fileExtension;
	}

	public function getFileExtension()
	{
		return $this->_fileExtension;
	}

	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}

	public function unregister()
	{
		spl_autoload_unregister(array($this, 'loadClass'));
	}

	public function loadClass($className)
	{
		if ($this->_namespace !== null && strpos($className, $this->_namespace.$this->_namespaceSeparator) !== 0) {
			return false;
		}
		$fname=($this->_includePath !== null ? $this->_includePath . DIRECTORY_SEPARATOR : '')
			.str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $className)
			.$this->_fileExtension;
		if (file_exists($fname)) {
			require $fname;
			return true;
		}
		else {
			return false;
		}
	}
}