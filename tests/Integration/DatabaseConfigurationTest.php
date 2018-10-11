<?php declare(strict_types=1);

namespace Tests\Integration;

use DateTime;
use DateTimeZone;
use Exception;
use Tests\Entity\Entity;
use Tests\TestCaseAbstract;

/**
 * Class DatabaseConfigurationTest
 *
 * @package Tests\Integration
 */
final class DatabaseConfigurationTest extends TestCaseAbstract
{

    /**
     * @throws Exception
     */
    public function testConnection(): void
    {
        $this->em->persist(
            (new Entity())
                ->setString('Entity')
                ->setInt(1)
                ->setFloat(1.1)
                ->setBool(TRUE)
                ->setDate(new DateTime('today', new DateTimeZone('UTC')))
        );
        $this->em->flush();
        $this->em->clear();

        /** @var Entity[] $entities */
        $entities = $this->em->getRepository(Entity::class)->findAll();
        self::assertEquals(1, count($entities));
        self::assertEquals('Entity', $entities[0]->getString());
    }

}