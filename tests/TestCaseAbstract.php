<?php declare(strict_types=1);

namespace Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use DoctrineExtensions\Query\Mysql\DateFormat;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TestCaseAbstract
 *
 * @package Tests
 */
abstract class TestCaseAbstract extends TestCase
{

    private const TEMP_DIR = '%s/../temp/Doctrine2.ORM';
    private const HOSTNAME = 'mariadb';
    private const DATABASE = 'datagrid';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $reader = new AnnotationReader();
        $driver = new MappingDriverChain();
        $driver->addDriver(new AnnotationDriver($reader, [sprintf('%s/Entity', __DIR__)]), 'Tests\\Entity');

        $configuration = Setup::createAnnotationMetadataConfiguration(
            [sprintf('%s/Entity', __DIR__)],
            FALSE,
            sprintf(self::TEMP_DIR, __DIR__),
            new FilesystemCache(sprintf(self::TEMP_DIR, __DIR__))
        );
        $configuration->setMetadataDriverImpl($driver);
        $configuration->setProxyNamespace('Proxy');
        $configuration->setProxyDir(sprintf(self::TEMP_DIR, __DIR__));
        $configuration->setNamingStrategy(new UnderscoreNamingStrategy());
        $configuration->addCustomStringFunction('DATE_FORMAT', DateFormat::class);

        $this->em = EntityManager::create([
            'driver'   => 'pdo_mysql',
            'host'     => self::HOSTNAME,
            'user'     => 'root',
            'password' => 'root',
            'dbname'   => self::DATABASE,
        ], $configuration);

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($this->em->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->em->getMetadataFactory()->getAllMetadata());
    }

}
