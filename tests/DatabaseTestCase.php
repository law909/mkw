<?php

namespace Tests;

use Doctrine\Common\EventManager;
use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Gedmo\Timestampable\TimestampableListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * Memóriabeli SQLite EntityManager, benne csak a teszt által kért entitások táblái. A fejlesztői
 * MySQL adatbázisokat közösen használja a futó alkalmazás, a tesztek oda nem írhatnak.
 */
abstract class DatabaseTestCase extends TestCase
{

    protected EntityManager $em;

    /**
     * @return string[] az entitások, amiknek a tábláját létre kell hozni
     */
    abstract protected function getEntityClasses(): array;

    protected function setUp(): void
    {
        parent::setUp();
        $this->em = $this->createEntityManager();
        (new SchemaTool($this->em))->createSchema(
            array_map(fn($class) => $this->em->getClassMetadata($class), $this->getEntityClasses())
        );
    }

    protected function tearDown(): void
    {
        $this->em->close();
        parent::tearDown();
    }

    private function createEntityManager(): EntityManager
    {
        $config = new Configuration();
        $config->setMetadataCache(new ArrayAdapter());
        $config->setQueryCache(new ArrayAdapter());
        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_EVAL);
        $config->setMetadataDriverImpl(ORMSetup::createDefaultAnnotationDriver([dirname(__DIR__) . '/Entities']));

        $evm = new EventManager();
        $evm->addEventSubscriber(new TimestampableListener());

        return EntityManager::create(['driver' => 'pdo_sqlite', 'memory' => true], $config, $evm);
    }

}
