<?php declare(strict_types=1);

namespace DataGridTests\Integration;

use DataGridTests\Entity\Entity;
use DataGridTests\Filter\EntityFilter;
use DataGridTests\TestCaseAbstract;
use DateTime;
use DateTimeZone;
use Exception;
use Hanaboso\DataGrid\Exception\GridException;
use Hanaboso\DataGrid\GridRequestDto;

/**
 * Class FilterTest
 *
 * @package DataGridTests\Integration
 */
final class FilterTest extends TestCaseAbstract
{

    private const DATETIME = 'Y-m-d H:i:s';

    private const SORTER         = 'sorter';
    private const FILTER         = 'filter';
    private const PAGE           = 'page';
    private const SEARCH         = 'search';
    private const ITEMS_PER_PAGE = 'itemsPerPage';

    protected const PAGING = 'paging';

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
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([]), ['date']);
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
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'id',
                    'direction' => 'ASC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'id',
                    'direction' => 'DESC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'string',
                    'direction' => 'ASC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'string',
                    'direction' => 'DESC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'int',
                    'direction' => 'ASC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'int',
                    'direction' => 'DESC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'float',
                    'direction' => 'ASC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'float',
                    'direction' => 'DESC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'bool',
                    'direction' => 'ASC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'bool',
                    'direction' => 'DESC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'date',
                    'direction' => 'ASC',
                ],
            ],
        ]), ['date']);
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

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::SORTER => [
                [
                    'column'    => 'date',
                    'direction' => 'DESC',
                ],
            ],
        ]), ['date']);
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
            (new EntityFilter($this->em))->getData(new GridRequestDto([
                self::SORTER => [
                    [
                        'column'    => 'Unknown',
                        'direction' => 'ASC',
                    ],
                ],
            ]));
            self::assertEquals(TRUE, FALSE);
        } catch (GridException $e) {
            $this->assertEquals(GridException::SORT_COLS_ERROR, $e->getCode());
            $this->assertEquals("Column 'Unknown' cannot be used for sorting! Have you forgotten add it to 'DataGridTests\Filter\EntityFilter::orderCols'?",
                $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function testConditions(): void
    {
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => [
                [
                    [
                        'column'   => 'string',
                        'value'    => ['String 1'],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'int',
                        'value'    => ['2'],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'float',
                        'value'    => [3.3],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'bool',
                        'value'    => [TRUE],
                        'operator' => 'EQ',
                    ],
                ],
                [
                    [
                        'column'   => 'string',
                        'value'    => ['String 4'],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'date',
                        'value'    => [(clone $this->today)->modify('1 day')->format(self::DATETIME)],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]), ['date']);
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

        $dto    = new GridRequestDto([
            self::FILTER => [
                [
                    [
                        'column'   => 'int',
                        'value'    => [6, 7, 8],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]);
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
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
            'filter'       => '[[{"column":"int","value":[6,7,8],"operator":"EQ"}]]',
            'page'         => 1,
            'search'       => NULL,
            'itemsPerPage' => 10,
            'total'        => 3,
            'sorter'       => NULL,
        ], $dto->getParamsForHeader());

        $dto    = new GridRequestDto([
            self::SEARCH => '9',
        ]);
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
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
            'filter'       => '[]',
            'search'       => '9',
            'page'         => 1,
            'itemsPerPage' => 10,
            'total'        => 1,
            'sorter'       => NULL,
        ], $dto->getParamsForHeader());

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => [
                [
                    [
                        'column'   => 'int',
                        'value'    => [8],
                        'operator' => 'GTE',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'int',
                        'value'    => [8],
                        'operator' => 'GT',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'int',
                        'value'    => [1],
                        'operator' => 'LT',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'int',
                        'value'    => [1],
                        'operator' => 'LTE',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'custom_string',
                        'value'    => ['String 0'],
                        'operator' => 'EQ',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'string',
                        'operator' => 'EMPTY',
                    ],
                ],
            ],
        ]), ['date']);
        self::assertEquals([], $result);

        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([
            self::FILTER => [
                [
                    [
                        'column'   => 'string',
                        'operator' => 'NEMPTY',
                    ],
                ],
            ],
        ]), ['date']);
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
            self::FILTER => [
                [
                    [
                        'column'   => 'string',
                        'operator' => 'NEMPTY',
                    ],
                ],
            ],
        ]))->setAdditionalFilters(
            [
                [
                    [
                        'column'   => 'string',
                        'operator' => 'EMPTY',
                    ],
                ],
            ]
        ), ['date']);
        self::assertEquals([], $result);

        $dto    = new GridRequestDto([
            self::SEARCH => 'Unknown',
        ]);
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals([], $result);
        self::assertEquals([
            'filter'       => '[]',
            'page'         => 1,
            'itemsPerPage' => 10,
            'search'       => 'Unknown',
            'total'        => 0,
            'sorter'       => NULL,
        ], $dto->getParamsForHeader());
    }

    /**
     * @throws Exception
     */
    public function testPagination(): void
    {
        $dto    = new GridRequestDto([
            self::SORTER    => [
                [
                    'column'    => 'id',
                    'direction' => 'ASC',
                ],
            ], self::PAGING => [self::PAGE => '3', self::ITEMS_PER_PAGE => '2'],
        ]);
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
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
            'filter'       => '[]',
            'sorter'       => '[{"column":"id","direction":"ASC"}]',
            'page'         => 3,
            'itemsPerPage' => 2,
            'total'        => 10,
            'search'       => NULL,
        ], $dto->getParamsForHeader());

        $dto    = (new GridRequestDto([
            self::SORTER    => [
                [
                    'column'    => 'id',
                    'direction' => 'ASC',
                ],
            ], self::PAGING => [self::PAGE => '3'],
        ]))->setItemsPerPage(2);
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
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
            'filter'       => '[]',
            'sorter'       => '[{"column":"id","direction":"ASC"}]',
            'page'         => 3,
            'itemsPerPage' => 2,
            'search'       => NULL,
            'total'        => 10,
        ], $dto->getParamsForHeader());

        $document = (new EntityFilter($this->em));
        $this->setProperty($document, 'countQuery', NULL);
        $dto    = new GridRequestDto([
            self::SORTER    => [
                ['direction' => 'ASC', 'column' => 'id'],
            ], self::PAGING => [self::PAGE => '3', self::ITEMS_PER_PAGE => '2'],
        ]);
        $result = $document->getData($dto, ['date']);
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
            'filter'       => '[]',
            'sorter'       => '[{"direction":"ASC","column":"id"}]',
            'page'         => 3,
            'itemsPerPage' => 2,
            'search'       => NULL,
            'total'        => 10,
        ], $dto->getParamsForHeader());
    }

}
