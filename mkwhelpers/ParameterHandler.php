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

    public function getParam($key, $default = null, $sanitize = true) {
        if (array_key_exists('params', $this->params) && array_key_exists($key, $this->params['params'])) {
            $data = $this->trim($this->params['params'][$key]);
            if ($sanitize) {
                $data = \mkw\Store::getSanitizer()->sanitize($data);
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
                $data = \mkw\Store::getSanitizer()->sanitize($data);
            }
            return $this->trim($data);
        }
        else {
            return $default;
        }
    }

    public function getBoolParam($key, $default = false) {
        return $this->typeconverter->toBool($this->getParam($key, $default));
    }

    public function getNumParam($key, $default = 0) {
        return $this->typeconverter->toNum($this->getParam($key, $default));
    }

    public function getIntParam($key, $default = 0) {
        return $this->typeconverter->toInt($this->getParam($key, $default));
    }

    public function getFloatParam($key, $default = 0.0) {
        return $this->typeconverter->toFloat($this->getParam($key, $default));
    }

    public function getStringParam($key, $default = '') {
        return $this->typeconverter->toString($this->getParam($key, $default));
    }

    public function getOriginalStringParam($key, $default = '') {
        return $this->typeconverter->toString($this->getParam($key, $default, false));
    }

    public function getDateParam($key, $default = '') {
        return $this->typeconverter->toDate($this->getParam($key, $default));
    }

    public function getArrayParam($key, $default = array()) {
        return $this->typeconverter->toArray($this->getParam($key, $default));
    }

    public function getBoolRequestParam($key, $default = false) {
        return $this->typeconverter->toBool($this->getRequestParam($key, $default));
    }

    public function getNumRequestParam($key, $default = 0) {
        return $this->typeconverter->toNum($this->getRequestParam($key, $default));
    }

    public function getIntRequestParam($key, $default = 0) {
        return $this->typeconverter->toInt($this->getRequestParam($key, $default));
    }

    public function getFloatRequestParam($key, $default = 0.0) {
        return $this->typeconverter->toFloat($this->getRequestParam($key, $default));
    }

    public function getStringRequestParam($key, $default = '') {
        return $this->typeconverter->toString($this->getRequestParam($key, $default));
    }

    public function getOriginalStringRequestParam($key, $default = '') {
        return $this->typeconverter->toString($this->getRequestParam($key, $default, false));
    }

    public function getDateRequestParam($key, $default = '') {
        return $this->typeconverter->toDate($this->getRequestParam($key, $default));
    }

    public function getArrayRequestParam($key, $default = array()) {
        return $this->typeconverter->toArray($this->getRequestParam($key, $default));
    }

}
