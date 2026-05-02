<?php

namespace mkwhelpers;

class SmartyView extends View
{

    public function __construct($compiledtplpath, $tplpath, $tplfilename, $configdir = '', $cachedir = '')
    {
        $this->tplengine = new \Smarty();
        $this->registerPlugins();
        $this->tplengine->setTemplateDir($tplpath);
        $this->tplengine->setCompileDir($compiledtplpath);
        $this->tplengine->setConfigDir($configdir);
        $this->tplengine->setCacheDir($cachedir);
        $this->tplfile = $tplfilename;
    }

    public function setVar($variable, $data)
    {
        $this->tplengine->assign($variable, $data);
    }

    public function getTemplateResult()
    {
        return $this->tplengine->fetch($this->tplfile);
    }

    public function printTemplateResult($storePrevUri = false)
    {
        $this->tplengine->display($this->tplfile);
        if ($storePrevUri) {
            \mkw\store::storePrevUri();
        }
    }

    private function registerPlugins(): void
    {
        $this->tplengine->registerPlugin('modifier', 't', '\t');
        $this->tplengine->registerPlugin('modifier', 'at', '\at');
        $this->tplengine->registerPlugin('modifier', 'haveJog', '\haveJog');
        $this->tplengine->registerPlugin('modifier', 'bizformat', '\bizformat');
        $this->tplengine->registerPlugin('modifier', 'number_format', '\number_format');
        $this->tplengine->registerPlugin('modifier', 'prefixUrl', '\prefixUrl');

        $this->tplengine->registerPlugin('function', 't', function (array $params) {
            return \t($params['msgid'] ?? $params['text'] ?? $params['value'] ?? '');
        });

        $this->tplengine->registerPlugin('function', 'at', function (array $params) {
            return \at($params['msgid'] ?? $params['text'] ?? $params['value'] ?? '');
        });

        $this->tplengine->registerPlugin('function', 'haveJog', function (array $params) {
            return \haveJog($params['jog'] ?? $params['value'] ?? '');
        });

        $this->tplengine->registerPlugin('function', 'bizformat', function (array $params) {
            return \bizformat(
                $params['mit'] ?? $params['value'] ?? $params['num'] ?? 0,
                $params['mire'] ?? $params['decimals'] ?? false
            );
        });

        $this->tplengine->registerPlugin('function', 'number_format', function (array $params) {
            return \number_format(
                $params['num'] ?? $params['number'] ?? $params['value'] ?? 0,
                (int)($params['decimals'] ?? 0),
                $params['decimal_separator'] ?? $params['dec_point'] ?? '.',
                $params['thousands_separator'] ?? $params['thousands_sep'] ?? ','
            );
        });

        $this->tplengine->registerPlugin('function', 'prefixUrl', function (array $params) {
            return \prefixUrl(
                $params['prefix'] ?? $params['value'] ?? '',
                $params['url'] ?? ''
            );
        });
    }
}