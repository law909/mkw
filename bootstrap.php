<?php

require_once 'vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\PsrCachedReader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Gedmo\Blameable\BlameableListener;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Timestampable\TimestampableListener;
use Listeners\ArsavListener;
use mkw\store;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Doctrine\Common\EventManager;
use Listeners\BizonylatfejListener;
use Listeners\BankbizonylatfejListener;
use Listeners\BizonylattetelListener;
use Listeners\JogareszvetelListener;
use Listeners\KuponListener;
use Listeners\PartnerListener;
use Listeners\PenztarbizonylatfejListener;
use Listeners\RendezvenyListener;
use Listeners\MPTNGYSzakmaianyagListener;
use Listeners\TermekListener;
use Doctrine\DBAL\Event\Listeners;

$ini = parse_ini_file('config.ini');
$setini = parse_ini_file('setup.ini');

store::setConfig($ini);
store::setSetup($setini);

$config = new Configuration();

if (array_key_exists('cache', $ini)) {
    switch ($ini['cache']) {
        case 'apc':
            $metacache = new ApcuAdapter('mkwmetadata');
            $querycache = new ApcuAdapter('mkwquery');
            break;
        case 'file':
            $metacache = new PhpFilesAdapter('mkwmetadata');
            $querycache = new PhpFilesAdapter('mkwquery');
            break;
        default:
            $metacache = new ArrayAdapter();
            $querycache = new ArrayAdapter();
            break;
    }
} else {
    $metacache = new ArrayAdapter();
    $querycache = new ArrayAdapter();
}
$config->setMetadataCache($metacache);
$config->setQueryCache($querycache);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');
if ($ini['developer']) {
    $config->setAutoGenerateProxyClasses(true);
} else {
    $config->setAutoGenerateProxyClasses(false);
}

AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$annotationReader = new AnnotationReader;
$cachedAnnotationReader = new PsrCachedReader(
    $annotationReader,
    $metacache
);
$driverchain = new MappingDriverChain();
// Gedmo DoctrineExtensions annotations
Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
    $driverchain,
    $cachedAnnotationReader
);
// Doctrine annotations
$defaultDriverImpl = ORMSetup::createDefaultAnnotationDriver([__DIR__ . '/Entities']);
$driverchain->addDriver($defaultDriverImpl, 'Entities');

$config->setMetadataDriverImpl($driverchain);

if ($ini['sqllog']) {
    $config->setSQLLogger(new \mkwhelpers\FileSQLLogger('sql.log'));
}

$config->addCustomStringFunction('YEAR', 'mkwhelpers\year');
$config->addCustomStringFunction('NOW', 'mkwhelpers\now');
$config->addCustomStringFunction('IF', 'mkwhelpers\ifelse');
$config->addCustomStringFunction('RAND', 'mkwhelpers\rand');
$config->addCustomStringFunction('CURDATE', 'mkwhelpers\curdate');

$evm = new EventManager();
// new MysqlSessionInit('UTF8','utf8_hungarian_ci')
$evm->addEventSubscriber(new Listeners\SQLSessionInit('SET NAMES UTF8 COLLATE utf8_hungarian_ci'));

$sluggableListener = new SluggableListener;
$sluggableListener->setAnnotationReader($cachedAnnotationReader);
//$sluggableListener->setUrlizer();
$evm->addEventSubscriber($sluggableListener);

$timestampableListener = new TimestampableListener;
$timestampableListener->setAnnotationReader($cachedAnnotationReader);
$evm->addEventSubscriber($timestampableListener);

$blameableListener = new BlameableListener();
$blameableListener->setAnnotationReader($cachedAnnotationReader);
store::setBlameableListener($blameableListener);
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

$evm->addEventListener(['onFlush', 'prePersist'], new BizonylatfejListener());
$evm->addEventListener(['onFlush', 'prePersist'], new BankbizonylatfejListener());
$evm->addEventListener(['onFlush', 'prePersist'], new PenztarbizonylatfejListener());
$evm->addEventListener([Events::onFlush, Events::postFlush], new BizonylattetelListener());
$evm->addEventListener(['prePersist'], new KuponListener());
$evm->addEventListener(['prePersist'], new RendezvenyListener());
$evm->addEventListener(['onFlush'], new JogareszvetelListener());
$evm->addEventListener(['onFlush'], new PartnerListener());
$evm->addEventListener(['onFlush'], new MPTNGYSzakmaianyagListener());
$evm->addEventListener(['onFlush'], new ArsavListener());
$evm->addEventListener([Events::onFlush, Events::postFlush], new TermekListener());

$connectionOptions = [
    'driver' => $ini['db.driver'],
    'user' => $ini['db.username'],
    'password' => $ini['db.password'],
    'host' => $ini['db.host'],
    'dbname' => $ini['db.dbname'],
    'port' => $ini['db.port']
];

$entityManager = EntityManager::create($connectionOptions, $config, $evm);

mkw\store::setEm($entityManager);
