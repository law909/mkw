<?php
use \Doctrine\Common\EventManager;

require 'vendor/autoload.php';

$ini = parse_ini_file('config.ini');
$setini = parse_ini_file('setup.ini');

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Listeners');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('mkwhelpers');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('mkw');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('Controllers');
$classLoader->register();

mkw\store::setConfig($ini);
mkw\store::setSetup($setini);

$config = new Doctrine\ORM\Configuration();

// DriverChain
//$driverchain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
$driverchain = new \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain();
if (array_key_exists('cache', $ini) && $ini['cache'] === 'apc') {
    $metacache = new Doctrine\Common\Cache\ApcuCache();
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
$config->addCustomStringFunction('NOW', 'mkwhelpers\now');
$config->addCustomStringFunction('IF', 'mkwhelpers\ifelse');
$config->addCustomStringFunction('RAND', 'mkwhelpers\rand');

$config->setMetadataDriverImpl($driverchain);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
$config->setMetadataCacheImpl($metacache);
$config->setAutoGenerateProxyClasses(false);

if ($ini['sqllog']) {
	$config->setSQLLogger(new \mkwhelpers\FileSQLLogger('sql.log'));
}

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

$blameableListener = new \Gedmo\Blameable\BlameableListener();
$blameableListener->setAnnotationReader($cachedAnnotationReader);
\mkw\store::setBlameableListener($blameableListener);
$evm->addEventSubscriber($blameableListener);

if (mkw\store::isMultilang()) {
    $translatableListener = new Gedmo\Translatable\TranslatableListener();
    $translatableListener->setAnnotationReader($cachedAnnotationReader);
    $translatableListener->setDefaultLocale('hu_hu');
    $translatableListener->setTranslatableLocale('hu_hu');
    $translatableListener->setTranslationFallback(true);
    $evm->addEventSubscriber($translatableListener);
    mkw\store::setTranslationListener($translatableListener);
}

$evm->addEventListener(array('onFlush', 'prePersist'), new Listeners\BizonylatfejListener());
$evm->addEventListener(array('onFlush', 'prePersist'), new Listeners\BankbizonylatfejListener());
$evm->addEventListener(array('onFlush', 'prePersist'), new Listeners\PenztarbizonylatfejListener());
$evm->addEventListener(array('onFlush'), new Listeners\BizonylattetelListener());
$evm->addEventListener(array('prePersist'), new Listeners\KuponListener());

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $evm);

//Zend_Session::start();

mkw\store::setEm($em);
