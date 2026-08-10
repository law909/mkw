<?php

namespace Controllers;

class errortestController extends \mkwhelpers\Controller
{

    public function run()
    {
        \mkw\store::writelog('/admin/hiba');
        // Szándékosan elkapatlan: az index.php nem fogja meg, így a PHP fatal
        // errorként a php-fpm error_log-jába (storage/logs/php.log) kerül.
        throw new \RuntimeException('/admin/hiba - szandekosan dobott teszthiba');
    }
}
