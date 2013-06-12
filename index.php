<?php
use SIIKerES\Store;
use \Entities, \Controllers\CentralController, \SIIKerES\generalDataLoader;
date_default_timezone_set('Europe/Budapest');

require_once('bootstrap.php');

$__translate = new Zend_Translate(
    array(
        'adapter' => 'array',
        'content' => 'locales/hu.php',
        'locale'  => 'hu'
    )
);

function write_log($text) {
 $handle=fopen("log.txt","a");
 $log="";
 $separator=" ## ";
 $log.=date('Y.m.d. H:i:s').$separator;
 $log.=$text;
 $log.="\n";
 fwrite($handle,$log);
 fclose($handle);
}

function t($msgid) {
	global $__translate;
	return $__translate->_($msgid);
}

$gdl=new generalDataLoader();
$cc=new CentralController($gdl);
$cc->handleRequest();