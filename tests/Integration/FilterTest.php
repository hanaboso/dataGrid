<?php declare(strict_types=1);

namespace Tests\Integration;

use DateTime;
use DateTimeZone;
use Exception;
use Hanaboso\DataGrid\Exception\GridException;
use Hanaboso\DataGrid\GridRequestDto;
use Tests\Entity\Entity;
use Tests\Filter\EntityFilter;
use Tests\PrivateTrait;
use Tests\TestCaseAbstract;

/**
 * Class FilterTest
 *
 * @package Tests\Integration
 */
final class FilterTest extends TestCaseAbstract
{

    use PrivateTrait;

    private const DATETIME = 'Y-m-d H:i:s';

    private const ORDER           = 'orderBy';
    private const FILTER          = 'filter';
    private const ADVANCED_FILTER = 'advanced_filter';
    private const PAGE            = 'page';
    private const LIMIT           = 'limit';

    /**
     * @var DateTime
     */
    private $today;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->today = new DateTime('today', new DateTimeZone('UTC'));

        for ($i = 0; $i < 10; $i++) {
            $this->em->persist(
                (new Entity())
                    ->setString(sprintf('String %s', $i))
                    ->setInt($i)
                    ->setFloat((float) sprintf('%s.%s', $i, $i))
                    ->setBool($i % 2 === 0)
                    ->setDate(new DateTime(sprintf('today +%s day', $i), new DateTimeZone('UTC')))
            );
        }

        $this->em->flush();
    }

    /**
     * @throws Exception
     */
    public function testBasic(): void
    {
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);
    }

    /**
     * @throws Exception
     */
    public function testSortations(): void
    {
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+id']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '-id']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+string']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '-string']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+int']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '-int']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+float']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '-float']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+bool']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('9 day')->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('3 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('4 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-8 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '-bool']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('8 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-6 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('8 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+date']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-9 day')->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '-date']))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ],
        ], $result);

        try {
            (new EntityFilter($this->em))->getData(new GridRequestDto([self::ORDER => '+Unknown']))->toArray();
            self::assertEquals(TRUE, FALSE);
        } catch (GridException $e) {
            $this->assertEquals(GridException::SORT_COLS_ERROR, $e->getCode());
            $this->assertEquals('Column [Unknown] is not defined for sorting.', $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function testConditions(): void
    {
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"string": "String 1"}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"int": 2}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"float": 3.3}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"bool": true, "string": "String 4"}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => sprintf('{"date": "%s"}', (clone $this->today)->modify('1 day')->format(self::DATETIME)),
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $dto    = new GridRequestDto([self::FILTER => ['{"int": [6, 7, 8]}']]);
        $result = (new EntityFilter($this->em))->getData($dto)->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);
        self::assertEquals([
            'filter'  => ['int' => '6,7,8'],
            'page'    => NULL,
            'limit'   => 10,
            'total'   => 3,
            'orderby' => NULL,
        ], $dto->getParamsForHeader());

        $dto    = new GridRequestDto([self::FILTER => '{"_MODIFIER_SEARCH": "9"}']);
        $result = (new EntityFilter($this->em))->getData($dto)->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),

            ],
        ], $result);
        self::assertEquals([
            'filter'  => ['search' => '9'],
            'page'    => NULL,
            'limit'   => 10,
            'total'   => 1,
            'orderby' => NULL,
        ], $dto->getParamsForHeader());

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"int_gte": 8}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),

            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),

            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"int_gt": 8}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),

            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"int_lt": 1}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-9 day')->format(self::DATETIME),

            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"int_lte": 1}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),

            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),

            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"custom_string": "String 0"}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"string": null}',
        ]))->toArray();
        self::assertEquals([], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => '{"string": "_MODIFIER_VAL_NOT_NULL"}',
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 0',
                'int'    => 0,
                'float'  => 0.0,
                'bool'   => TRUE,
                'date'   => $this->today->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[2]['id'],
                'string' => 'String 2',
                'int'    => 2,
                'float'  => 2.2,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[3]['id'],
                'string' => 'String 3',
                'int'    => 3,
                'float'  => 3.3,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[4]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[5]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[6]['id'],
                'string' => 'String 6',
                'int'    => 6,
                'float'  => 6.6,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[7]['id'],
                'string' => 'String 7',
                'int'    => 7,
                'float'  => 7.7,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[8]['id'],
                'string' => 'String 8',
                'int'    => 8,
                'float'  => 8.8,
                'bool'   => TRUE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[9]['id'],
                'string' => 'String 9',
                'int'    => 9,
                'float'  => 9.9,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData((new GridRequestDto([
            self::FILTER => '{"string": "_MODIFIER_VAL_NOT_NULL"}',
        ]))->setAdditionalFilters(['string' => NULL]))->toArray();
        self::assertEquals([], $result);

        $dto    = new GridRequestDto([self::FILTER => '{"search": "Unknown"}']);
        $result = (new EntityFilter($this->em))->getData($dto)->toArray();
        self::assertEquals([], $result);
        self::assertEquals([
            'filter'  => ['search' => 'Unknown'],
            'page'    => NULL,
            'limit'   => 10,
            'total'   => 0,
            'orderby' => NULL,
        ], $dto->getParamsForHeader());
    }

    /**
     * @throws Exception
     */
    public function testPagination(): void
    {
        $dto    = new GridRequestDto([self::ORDER => '+id', self::PAGE => '3', self::LIMIT => '2']);
        $result = (new EntityFilter($this->em))->getData($dto)->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('4 day')->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);
        self::assertEquals([
            'filter'  => [],
            'orderby' => '+id',
            'page'    => 3,
            'limit'   => 2,
            'total'   => 10,
        ], $dto->getParamsForHeader());

        $dto    = (new GridRequestDto([self::ORDER => '+id', self::PAGE => '3']))->setLimit(2);
        $result = (new EntityFilter($this->em))->getData($dto)->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);
        self::assertEquals([
            'filter'  => [],
            'orderby' => '+id',
            'page'    => 3,
            'limit'   => 2,
            'total'   => 10,
        ], $dto->getParamsForHeader());

        $document = (new EntityFilter($this->em));
        $this->setProperty($document, 'countQuery', NULL);
        $dto    = new GridRequestDto([self::ORDER => '+id', self::PAGE => '3', self::LIMIT => '2']);
        $result = $document->getData($dto)->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 4',
                'int'    => 4,
                'float'  => 4.4,
                'bool'   => TRUE,
                'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
            ], [
                'id'     => $result[1]['id'],
                'string' => 'String 5',
                'int'    => 5,
                'float'  => 5.5,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);
        self::assertEquals([
            'filter'  => [],
            'orderby' => '+id',
            'page'    => 3,
            'limit'   => 2,
            'total'   => 10,
        ], $dto->getParamsForHeader());
    }

    /**
     * @throws Exception
     */
    public function testAdvancedConditions(): void
    {
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::ADVANCED_FILTER => json_encode([
                [
                    [
                        'column'    => 'string',
                        'operation' => 'EQ',
                        'value'     => 'String 1',
                    ], [
                        'column'    => 'int',
                        'operation' => 'EQ',
                        'value'     => 1,
                    ], [
                        'column'    => 'bool',
                        'operation' => 'EQ',
                        'value'     => TRUE,
                    ],
                ], [
                    [
                        'column'    => 'bool',
                        'operation' => 'EQ',
                        'value'     => FALSE,
                    ],
                ],
            ]),
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->modify('1 day')->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::ADVANCED_FILTER => json_encode([
                [
                    [
                        'column'    => 'string',
                        'operation' => 'LIKE',
                        'value'     => 'ri',
                    ],
                ], [
                    [
                        'column'    => 'string',
                        'operation' => 'STARTS',
                        'value'     => 'St',
                    ],
                ], [
                    [
                        'column'    => 'string',
                        'operation' => 'ENDS',
                        'value'     => 'ng 1',
                    ],
                ],
            ]),
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ],
        ], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER          => '{"string": "String 1"}',
            self::ADVANCED_FILTER => json_encode([
                [
                    [
                        'column'    => 'string',
                        'operation' => 'STARTS',
                        'value'     => 'St',
                    ],
                ], [
                    [
                        'column'    => 'string',
                        'operation' => 'LIKE',
                        'value'     => 'ri',
                    ],
                ], [
                    [
                        'column'    => 'string',
                        'operation' => 'ENDS',
                        'value'     => 'ng 1',
                    ],
                ], [
                    [
                        'column'    => 'custom_string',
                        'operation' => 'EQ',
                        'value'     => 'String 1',
                    ],
                ],
            ]),
        ]))->toArray();
        self::assertEquals([
            [
                'id'     => $result[0]['id'],
                'string' => 'String 1',
                'int'    => 1,
                'float'  => 1.1,
                'bool'   => FALSE,
                'date'   => $this->today->format(self::DATETIME),
            ],
        ], $result);
    }

}