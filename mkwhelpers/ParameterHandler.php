<?php

namespace mkwhelpers;

require_once 'IParameterHandler.php';
require_once 'TypeConverter.php';

class ParameterHandler implements IParameterHandler {

    private $params;
    private $typeconverter;

    public function __construct($par = null) {
        $this->params = $par;
        $this->typeconverter = new TypeConverter();
    }

    public function trim($arr) {
        if (is_array($arr)) {
            array_walk_recursive($arr, function(&$val) {
                $val = trim($val);
            });
            return $arr;
        }
        else {
            return trim($arr);
        }
    }

    public function asArray() {
        return $this->params;
    }

    public function existsParam($key) {
        return array_key_exists('params', $this->params) && array_key_exists($key, $this->params['params']);
    }

    public function existsRequestParam($key) {
        return array_key_exists('requestparams', $this->params) && array_key_exists($key, $this->params['requestparams']);
    }

    public function getParam($key, $default = null, $sanitize = true) {
        if (array_key_exists('params', $this->params) && array_key_exists($key, $this->params['params'])) {
            $data = $this->trim($this->params['params'][$key]);
            if ($sanitize) {
                $data = \mkw\store::getSanitizer()->sanitize($data);
            }
            return $this->trim($data);
        }
        else {
            return $default;
        }
    }

    public function getRequestParam($key, $default = null, $sanitize = true) {
        if (array_key_exists('requestparams', $this->params) && array_key_exists($key, $this->params['requestparams'])) {
            $data = $this->trim($this->params['requestparams'][$key]);
            if ($sanitize) {
                $data = \mkw\store::getSanitizer()->sanitize($data);
            }
            return $this->trim($data);
        }
        else {
            return $default;
        }
    }

    public function getBoolParam($key, $default = false) {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toBool($this->getParam($key, $default));
        }
        return $default;
    }

    public function getNumParam($key, $default = 0) {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toNum($this->getParam($key, $default));
        }
        return $default;
    }

    public function getIntParam($key, $default = 0) {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toInt($this->getParam($key, $default));
        }
        return $default;
    }

    public function getFloatParam($key, $default = 0.0) {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toFloat($this->getParam($key, $default));
        }
        return $default;
    }

    public function getStringParam($key, $default = '') {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toString($this->getParam($key, $default));
        }
        return $default;
    }

    public function getOriginalStringParam($key, $default = '') {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toString($this->getParam($key, $default, false));
        }
        return $default;
    }

    public function getDateParam($key, $default = '') {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toDate($this->getParam($key, $default));
        }
        return $default;
    }

    public function getArrayParam($key, $default = array()) {
        if ($this->existsParam($key)) {
            return $this->typeconverter->toArray($this->getParam($key, $default));
        }
        return $default;
    }

    public function getBoolRequestParam($key, $default = false) {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toBool($this->getRequestParam($key, $default));
        }
        return $default;
    }

    public function getNumRequestParam($key, $default = 0) {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toNum($this->getRequestParam($key, $default));
        }
        return $default;
    }

    public function getIntRequestParam($key, $default = 0) {
        if ($this->existsRequestParam($key)) {
        return $this->typeconverter->toInt($this->getRequestParam($key, $default));
            }
        return $default;
    }

    public function getFloatRequestParam($key, $default = 0.0) {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toFloat($this->getRequestParam($key, $default));
        }
        return $default;
    }

    public function getStringRequestParam($key, $default = '') {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toString($this->getRequestParam($key, $default));
        }
        return $default;
    }

    public function getOriginalStringRequestParam($key, $default = '') {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toString($this->getRequestParam($key, $default, false));
        }
        return $default;
    }

    public function getDateRequestParam($key, $default = '') {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toDate($this->getRequestParam($key, $default));
        }
        return $default;
    }

    public function getArrayRequestParam($key, $default = array()) {
        if ($this->existsRequestParam($key)) {
            return $this->typeconverter->toArray($this->getRequestParam($key, $default));
        }
        return $default;
    }

}
