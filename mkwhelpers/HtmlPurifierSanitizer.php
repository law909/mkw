<?php

namespace mkwhelpers;

require_once 'ISanitizer.php';

class HtmlPurifierSanitizer implements ISanitizer {

    private $purifier;

    public function __construct($conf = array()) {
        $config = \HTMLPurifier_Config::createDefault();
        // az alapértelmezett cache a vendor/-ban van: a webszerver fájljai miatt ott elakad a composer install
        $cacheDir = self::getCacheDir();
        if ($cacheDir) {
            $config->set('Cache.SerializerPath', $cacheDir);
        }
        else {
            $config->set('Cache.DefinitionImpl', null);
        }
        if ($conf) {
            foreach ($conf as $k => $v) {
                $config->set($k, $v);
            }
        }
        else {
            $config->set('HTML.Allowed', '');
        }
        $this->purifier = new \HTMLPurifier($config);
    }

    private static function getCacheDir() {
        $dir = \mkw\store::storagePath('htmlpurifier');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        if (is_dir($dir) && !is_file($dir . '/.htaccess')) {
            @file_put_contents($dir . '/.htaccess', "Require all denied\n");
        }
        return is_writable($dir) ? $dir : null;
    }

    public function sanitize($data) {
        if (is_array($data)) {
            $puri = $this->purifier;
            array_walk_recursive($data, function(&$val) use ($puri) {
                $val = $puri->purify($val);
            });
            return $data;
        }
        else {
            return $this->purifier->purify($data);
        }
    }

}
