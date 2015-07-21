<?php
use \Doctrine\Common\EventManager,
	\mkw\store;

require 'vendor/autoload.php';

//require_once 'Doctrine/Common/ClassLoader.php';

$ini = parse_ini_file('config.ini');
$setini = parse_ini_file('setup.ini');


$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

//$classLoader=new \Doctrine\Common\ClassLoader('Doctrine');
//$classLoader->register();
//
$classLoader=new \Doctrine\Common\ClassLoader('mkwhelpers');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('mkw');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('Controllers');
$classLoader->register();

$config = new Doctrine\ORM\Configuration();

// DriverChain
$driverchain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
if (array_key_exists('cache', $ini) && $ini['cache'] === 'apc') {
    $metacache = new Doctrine\Common\Cache\ApcCache();
}
else {
    $metacache = new Doctrine\Common\Cache\ArrayCache();
}
$cachedAnnotationReader = new Doctrine\Common\Annotations\CachedReader(
    new Doctrine\Common\Annotations\AnnotationReader,
    $metacache
);
// Gedmo DoctrineExtensions annotations
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
    $driverchain,
    $cachedAnnotationReader
);
// Doctrine annotations
$doctrinedriver = $config->newDefaultAnnotationDriver(array(__DIR__.'/Entities'), false);
$driverchain->addDriver($doctrinedriver, 'Entities');

$config->addCustomStringFunction('YEAR', 'mkwhelpers\year');
$config->addCustomStringFunction('IF', 'mkwhelpers\ifelse');

$config->setMetadataDriverImpl($driverchain);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setMetadataCacheImpl($metacache);
$config->setAutoGenerateProxyClasses(false);

if ($ini['sqllog']) {
	$config->setSQLLogger(new \mkwhelpers\FileSQLLogger('sql.log'));
}

$setupini=parse_ini_file('setup.ini');

$connectionOptions = array(
    'driver'=>$ini['db.driver'],
    'user'=>$ini['db.username'],
	'password'=>$ini['db.password'],
	'host'=>$ini['db.host'],
	'dbname'=>$ini['db.dbname'],
	'port'=>$ini['db.port']
);

$evm = new EventManager();

$evm->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit('UTF8','utf8_hungarian_ci'));

$sluggableListener = new Gedmo\Sluggable\SluggableListener;
$sluggableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($sluggableListener);

$timestampableListener = new Gedmo\Timestampable\TimestampableListener;
$timestampableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($timestampableListener);

if ($setini['multilang']) {
    $translatableListener=new Gedmo\Translatable\TranslatableListener();
    $translatableListener->setAnnotationReader($cachedAnnotationReader);
    $translatableListener->setDefaultLocale('hu_hu');
    $translatableListener->setTranslatableLocale('hu_hu');
    $translatableListener->setTranslationFallback(true);
    $evm->addEventSubscriber($translatableListener);
    store::setTranslationListener($translatableListener);
}

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $evm);

//Zend_Session::start();

store::setEm($em);
store::setConfig($ini);
store::setSetup($setupini);