<?php declare(strict_types=1);

namespace Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TestCaseAbstract
 *
 * @package Tests
 */
abstract class TestCaseAbstract extends TestCase
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {

        $this->em = EntityManager::create([
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => 'root',
            'dbname'   => 'datagrid',
        ], Setup::createAnnotationMetadataConfiguration([], TRUE));
    }

}