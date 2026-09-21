<?php

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

// mint a bootstrap.php-ban: az annotation reader nem autoloadolja az ORM és a Gedmo annotációs osztályait
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
\Gedmo\DoctrineExtensions::registerAnnotations();

date_default_timezone_set('Europe/Budapest');

// a tesztek nem olvassák a config.ini-t / setup.ini-t: ami kell, azt a teszt maga állítja be
\mkw\store::setConfig([]);
\mkw\store::setSetup([]);
