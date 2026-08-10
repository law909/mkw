<?php

namespace Controllers;

class errortestController extends \mkwhelpers\Controller
{

    public function run()
    {
        $this->triggerError('/admin/hiba');
    }

    public function publicRun()
    {
        $key = (string)\mkw\store::getConfigValue('hiba.key');
        if ($key === '' || !hash_equals($key, $this->params->getOriginalStringRequestParam('key'))) {
            header('HTTP/1.1 404 Not found');
            return;
        }
        $this->triggerError('/hiba');
    }

    private function triggerError($path)
    {
        \mkw\store::writelog($path);
        // Szándékosan elkapatlan: az index.php nem fogja meg, így a PHP fatal
        // errorként a php-fpm error_log-jába (storage/logs/php.log) kerül.
        throw new \RuntimeException($path . ' - szandekosan dobott teszthiba');
    }
}
