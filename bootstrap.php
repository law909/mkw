<?php
use \Doctrine\ORM\Configuration, \Doctrine\Common\EventManager,
	\Doctrine\DBAL\Event\Listeners\MysqlSessionInit, \mkw\store;

require_once 'Doctrine/Common/ClassLoader.php';

$ini=parse_ini_file('config.ini');

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

$classLoader=new \matt\ControllerClassLoader('Controllers',__DIR__);
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();

$config->addCustomStringFunction('YEAR', 'matt\year');
$config->addCustomStringFunction('IF', 'matt\ifelse');

$chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();

$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__.'/Entities'));
$translatableDriverImpl = $config->newDefaultAnnotationDriver($ini['path.doctrine'].'/DoctrineExtensions/lib/Gedmo/Translatable/Entity');

$chainDriverImpl->addDriver($driverImpl, 'Entities');
$chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');

$config->setMetadataDriverImpl($chainDriverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

if ($ini['developer']) {
	$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
	$config->setAutoGenerateProxyClasses(true);
	require_once('fb.php');
}
else {
	$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
	$config->setAutoGenerateProxyClasses(false);
	function fb() {};
}

if ($ini['sqllog']) {
	$config->setSQLLogger(new \matt\FileSQLLogger('sql.log'));
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
$translationListener=new Gedmo\Translatable\TranslationListener();
$translationListener->setTranslatableLocale('hu_hu');
$evm->addEventSubscriber($translationListener);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config,$evm);

//Zend_Session::start();

store::setEm($em);
store::setConfig($ini);
store::setSetup($setupini);