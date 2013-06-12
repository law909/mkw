<?php

date_default_timezone_set('Europe/Budapest');
require_once 'Doctrine/Common/ClassLoader.php';

$ini=parse_ini_file('config.ini');

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('Gedmo','DoctrineExtensions/lib');
$classLoader->register();

$classLoader=new \Doctrine\Common\ClassLoader('matt');
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);

$chainDriverImpl = new \Doctrine\ORM\Mapping\Driver\DriverChain();

$driverImpl = $config->newDefaultAnnotationDriver(array(__DIR__.'/Entities'));
$translatableDriverImpl = $config->newDefaultAnnotationDriver($ini['path.doctrine'].'/DoctrineExtensions/lib/Gedmo/Translatable/Entity');
$sluggableDriverImpl = $config->newDefaultAnnotationDriver($ini['path.doctrine'].'/DoctrineExtensions/lib/Gedmo/Sluggable');

$chainDriverImpl->addDriver($driverImpl, 'Entities');
$chainDriverImpl->addDriver($translatableDriverImpl, 'Gedmo\Translatable');
$chainDriverImpl->addDriver($sluggableDriverImpl, 'Gedmo\Sluggable');

$config->setMetadataDriverImpl($chainDriverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
    'driver'=>$ini['db.driver'],
    'user'=>$ini['db.username'],
	'password'=>$ini['db.password'],
	'host'=>$ini['db.host'],
	'dbname'=>$ini['db.dbname'],
	'port'=>$ini['db.port']
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));