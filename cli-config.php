<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
require_once 'bootstrap.php';

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    []
);