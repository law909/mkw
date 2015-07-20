<?php
use \Doctrine\ORM\Configuration, \Doctrine\Common\EventManager,
	\Doctrine\DBAL\Event\Listeners\MysqlSessionInit, \mkw\store;

require_once 'Doctrine/Common/ClassLoader.php';

$ini = parse_ini_file('config.ini');
$setini = parse_ini_file('setup.ini');

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('Doctrine');
$classLoader->register();

/**$autoloader = new \Doctrine\Common\ClassLoader('Zend');
$autoloader->setNamespaceSeparator('_');
$autoloader->register();
**/

$classLoader=new \Doctrine\Common\ClassLoader('Gedmo','Doctrine/DoctrineExtensions/lib');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('mkwhelpers');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('mkw');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('Controllers');
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();

$config->addCustomStringFunction('YEAR', 'mkwhelpers\year');
$config->addCustomStringFunction('IF', 'mkwhelpers\ifelse');

$chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();

$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__.'/Entities'));
$chainDriverImpl->addDriver($driverImpl, 'Entities');

if ($setini['multilang']) {
    $translatableDriverImpl = $config->newDefaultAnnotationDriver($ini['path.doctrine'].'/DoctrineExtensions/lib/Gedmo/Translatable/Entity');
    $chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');
}

$config->setMetadataDriverImpl($chainDriverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

if (array_key_exists('cache', $ini) && $ini['cache'] === 'apc') {
    $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcCache());
}
else {
	$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
}

if ($ini['developer']) {
	$config->setAutoGenerateProxyClasses(true);
//	require_once('fb.php');
}
else {
	$config->setAutoGenerateProxyClasses(false);
//	function fb() {}
}

if ($ini['sqllog']) {
	$config->setSQLLogger(new \mkwhelpers\FileSQLLogger('sql.log'));
}
$setupini=parse_ini_file('setup.ini');

if (strtolower($ini['tplengine'])=='dwoo') {
	require_once('dwooAutoload.php');
}

require 'vendor/autoload.php';

$connectionOptions = array(
    'driver'=>$ini['db.driver'],
    'user'=>$ini['db.username'],
	'password'=>$ini['db.password'],
	'host'=>$ini['db.host'],
	'dbname'=>$ini['db.dbname'],
	'port'=>$ini['db.port']
);

$evm = new EventManager();
$evm->addEventSubscriber(new MysqlSessionInit('UTF8','utf8_hungarian_ci'));
$evm->addEventSubscriber(new \Gedmo\Sluggable\SluggableListener());
$evm->addEventSubscriber(new \Gedmo\Timestampable\TimestampableListener());
if ($setini['multilang']) {
    $translationListener=new Gedmo\Translatable\TranslationListener();
    $translationListener->setDefaultLocale('hu_hu');
    $translationListener->setTranslatableLocale('hu_hu');
    $translationListener->setTranslationFallback(true);
    $evm->addEventSubscriber($translationListener);
    store::setTranslationListener($translationListener);
}

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $evm);

//Zend_Session::start();

store::setEm($em);
store::setConfig($ini);
store::setSetup($setupini);