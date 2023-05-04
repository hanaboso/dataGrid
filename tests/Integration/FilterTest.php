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
use LogicException;

/**
 * Class FilterTest
 *
 * @package DataGridTests\Integration
 */
final class FilterTest extends TestCaseAbstract
{

    protected const PAGING = 'paging';

    private const DATETIME       = 'Y-m-d H:i:s';
    private const SORTER         = 'sorter';
    private const FILTER         = 'filter';
    private const PAGE           = 'page';
    private const SEARCH         = 'search';
    private const ITEMS_PER_PAGE = 'itemsPerPage';

    /**
     * @var DateTime
     */
    private DateTime $today;

    /**
     * @throws Exception
     */
    public function testBasic(): void
    {
        $result = (new EntityFilter($this->em))->getData(new GridRequestDto([]), ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testSortations(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'id',
                            'direction' => 'ASC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'id',
                            'direction' => 'DESC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[1]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[2]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[3]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[4]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[5]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[6]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[7]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[8]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[9]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'string',
                            'direction' => 'ASC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'string',
                            'direction' => 'DESC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[1]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[2]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[3]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[4]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[5]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[6]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[7]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[8]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[9]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'int',
                            'direction' => 'ASC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'int',
                            'direction' => 'DESC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[1]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[2]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[3]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[4]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[5]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[6]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[7]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[8]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[9]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'float',
                            'direction' => 'ASC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'float',
                            'direction' => 'DESC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[1]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[2]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[3]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[4]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[5]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[6]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[7]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[8]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[9]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'bool',
                            'direction' => 'ASC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('9 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[1]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[2]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[4]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('3 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[5]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[6]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('4 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[7]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('2 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-8 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[9]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'bool',
                            'direction' => 'DESC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('8 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[1]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-6 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('2 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[3]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('2 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[4]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[5]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[6]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[7]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-2 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[8]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('8 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'date',
                            'direction' => 'ASC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-9 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::SORTER => [
                        [
                            'column'    => 'date',
                            'direction' => 'DESC',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[1]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[2]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[3]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[4]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[5]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[6]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[7]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[8]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[9]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        try {
            (new EntityFilter($this->em))->getData(
                new GridRequestDto(
                    [
                        self::SORTER => [
                            [
                                'column'    => 'Unknown',
                                'direction' => 'ASC',
                            ],
                        ],
                    ],
                ),
            );
            self::assertEquals(TRUE, FALSE);
        } catch (GridException $e) {
            self::assertEquals(GridException::SORT_COLS_ERROR, $e->getCode());
            self::assertEquals(
                "Column 'Unknown' cannot be used for sorting! Have you forgotten add it to 'DataGridTests\Filter\EntityFilter::orderCols'?",
                $e->getMessage(),
            );
        }
    }

    /**
     * @throws Exception
     */
    public function testConditions(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'EQ',
                                'value'    => ['String 1'],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'EQ',
                                'value'    => ['2'],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[0]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'float',
                                'operator' => 'EQ',
                                'value'    => [3.3],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[0]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'bool',
                                'operator' => 'EQ',
                                'value'    => [TRUE],
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'EQ',
                                'value'    => ['String 4'],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[0]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'date',
                                'operator' => 'EQ',
                                'value'    => [(clone $this->today)->modify('1 day')->format(self::DATETIME)],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[0]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ],
            ],
            $result,
        );

        $dto    = new GridRequestDto(
            [
                self::FILTER => [
                    [
                        [
                            'column'   => 'int',
                            'operator' => 'EQ',
                            'value'    => [6, 7],
                        ],
                    ],
                ],
            ],
        );
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[0]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[[{"column":"int","operator":"EQ","value":[6,7]}]]',
                'itemsPerPage' => 10,
                'page'         => 1,
                'search'       => NULL,
                'sorter'       => NULL,
                'total'        => 1,
            ],
            $dto->getParamsForHeader(),
        );

        $dto    = new GridRequestDto(
            [
                self::FILTER => [
                    [
                        [
                            'column'   => 'int',
                            'operator' => 'IN',
                            'value'    => [6, 7, 8],
                        ],
                    ],
                ],
            ],
        );
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[0]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[1]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[2]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[[{"column":"int","operator":"IN","value":[6,7,8]}]]',
                'itemsPerPage' => 10,
                'page'         => 1,
                'search'       => NULL,
                'sorter'       => NULL,
                'total'        => 3,
            ],
            $dto->getParamsForHeader(),
        );

        $dto    = new GridRequestDto(
            [
                self::FILTER => [
                    [
                        [
                            'column'   => 'int',
                            'operator' => 'NIN',
                            'value'    => [0, 1, 2, 3, 4, 5, 6, 7, 9],
                        ],
                    ],
                ],
            ],
        );
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[0]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[[{"column":"int","operator":"NIN","value":[0,1,2,3,4,5,6,7,9]}]]',
                'itemsPerPage' => 10,
                'page'         => 1,
                'search'       => NULL,
                'sorter'       => NULL,
                'total'        => 1,
            ],
            $dto->getParamsForHeader(),
        );

        $dto    = new GridRequestDto(
            [
                self::SEARCH => '9',
            ],
        );
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',

                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[]',
                'itemsPerPage' => 10,
                'page'         => 1,
                'search'       => '9',
                'sorter'       => NULL,
                'total'        => 1,
            ],
            $dto->getParamsForHeader(),
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'GTE',
                                'value'    => [8],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[0]['id'],
                    'int'    => 8,
                    'string' => 'String 8',

                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[1]['id'],
                    'int'    => 9,
                    'string' => 'String 9',

                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'GT',
                                'value'    => [8],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[0]['id'],
                    'int'    => 9,
                    'string' => 'String 9',

                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'LT',
                                'value'    => [1],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-9 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',

                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'LTE',
                                'value'    => [1],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',

                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',

                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'custom_string',
                                'operator' => 'EQ',
                                'value'    => ['String 0'],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'EMPTY',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals([], $result);

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEMPTY',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[1]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[2]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 3.3,
                    'id'     => $result[3]['id'],
                    'int'    => 3,
                    'string' => 'String 3',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[4]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[5]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 6.6,
                    'id'     => $result[6]['id'],
                    'int'    => 6,
                    'string' => 'String 6',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 7.7,
                    'id'     => $result[7]['id'],
                    'int'    => 7,
                    'string' => 'String 7',
                ], [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 8.8,
                    'id'     => $result[8]['id'],
                    'int'    => 8,
                    'string' => 'String 8',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[9]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            (new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEMPTY',
                            ],
                        ],
                    ],
                ],
            ))->setAdditionalFilters(
                [
                    [
                        [
                            'column'   => 'string',
                            'operator' => 'EMPTY',
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals([], $result);

        $dto    = new GridRequestDto(
            [
                self::SEARCH => 'Unknown',
            ],
        );
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals([], $result);
        self::assertEquals(
            [
                'filter'       => '[]',
                'itemsPerPage' => 10,
                'page'         => 1,
                'search'       => 'Unknown',
                'sorter'       => NULL,
                'total'        => 0,
            ],
            $dto->getParamsForHeader(),
        );
    }

    /**
     * @throws Exception
     */
    public function testPagination(): void
    {
        $dto    = new GridRequestDto(
            [
                self::PAGING => [self::PAGE => '3', self::ITEMS_PER_PAGE => '2'],
                self::SORTER    => [
                    [
                        'column'    => 'id',
                        'direction' => 'ASC',
                    ],
                ],
            ],
        );
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('4 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[0]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[1]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[]',
                'itemsPerPage' => 2,
                'page'         => 3,
                'search'       => NULL,
                'sorter'       => '[{"column":"id","direction":"ASC"}]',
                'total'        => 10,
            ],
            $dto->getParamsForHeader(),
        );

        $dto    = (new GridRequestDto(
            [
                self::PAGING => [self::PAGE => '3'],
                self::SORTER    => [
                    [
                        'column'    => 'id',
                        'direction' => 'ASC',
                    ],
                ],
            ],
        ))->setItemsPerPage(2);
        $result = (new EntityFilter($this->em))->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[0]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[1]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[]',
                'itemsPerPage' => 2,
                'page'         => 3,
                'search'       => NULL,
                'sorter'       => '[{"column":"id","direction":"ASC"}]',
                'total'        => 10,
            ],
            $dto->getParamsForHeader(),
        );

        $document = (new EntityFilter($this->em));
        $this->setProperty($document, 'countQuery', NULL);
        $dto    = new GridRequestDto(
            [
                self::PAGING => [self::PAGE => '3', self::ITEMS_PER_PAGE => '2'],
                self::SORTER    => [
                    ['direction' => 'ASC', 'column' => 'id'],
                ],
            ],
        );
        $result = $document->getData($dto, ['date']);
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('-1 day')->format(self::DATETIME),
                    'float'  => 4.4,
                    'id'     => $result[0]['id'],
                    'int'    => 4,
                    'string' => 'String 4',
                ], [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 5.5,
                    'id'     => $result[1]['id'],
                    'int'    => 5,
                    'string' => 'String 5',
                ],
            ],
            $result,
        );
        self::assertEquals(
            [
                'filter'       => '[]',
                'itemsPerPage' => 2,
                'page'         => 3,
                'search'       => NULL,
                'sorter'       => '[{"direction":"ASC","column":"id"}]',
                'total'        => 10,
            ],
            $dto->getParamsForHeader(),
        );
    }

    /**
     * @throws Exception
     */
    public function testGetFilter(): void
    {
        $dto = new GridRequestDto(
            [
                self::FILTER => [[['column' => 'a', 'operator' => 'b']]],
            ],
        );

        self::assertNotEmpty($dto->getFilter(FALSE));
    }

    /**
     * @throws Exception
     */
    public function testGetFilterNotArray(): void
    {
        $dto = new GridRequestDto(
            [
                self::FILTER => ['a'],
            ],
        );

        self::expectException(GridException::class);
        $dto->getFilter(FALSE);
    }

    /**
     * @throws Exception
     */
    public function testGetFilterMissingFields(): void
    {
        $dto = new GridRequestDto(
            [
                self::FILTER => [[[]]],
            ],
        );

        self::expectException(GridException::class);
        $dto->getFilter(FALSE);
    }

    /**
     * @throws Exception
     */
    public function testGetOrderBy(): void
    {
        $dto = new GridRequestDto(
            [
                self::SORTER => [['column' => 'a', 'direction' => 'b']],
            ],
        );

        self::expectException(GridException::class);
        $dto->getOrderBy();
    }

    /**
     * @throws Exception
     */
    public function testGetOrderByNotArray(): void
    {
        $dto = new GridRequestDto(
            [
                self::SORTER => ['a'],
            ],
        );

        self::expectException(GridException::class);
        $dto->getOrderBy();
    }

    /**
     * @throws Exception
     */
    public function testGetOrderByMissingFields(): void
    {
        $dto = new GridRequestDto(
            [
                self::SORTER => [[[]]],
            ],
        );

        self::expectException(GridException::class);
        $dto->getOrderBy();
    }

    /**
     * @throws Exception
     */
    public function testProcessConditionBadFormat(): void
    {
        $dto = new GridRequestDto(
            [
                self::FILTER => [[['column' => 'a', 'operator' => 'b']]],
            ],
        );

        self::expectException(LogicException::class);
        (new EntityFilter($this->em))->getData($dto);
    }

    /**
     * @throws Exception
     */
    public function testProcessConditionMissingSearchFields(): void
    {
        $dto = new GridRequestDto(
            [
                self::SEARCH => 'a',
            ],
        );

        $f = (new EntityFilter($this->em));
        $this->setProperty($f, 'searchableCols', []);

        self::expectException(GridException::class);
        self::expectExceptionCode(GridException::SEARCH_COLS_ERROR);
        $f->getData($dto);
    }

    /**
     * @throws Exception
     */
    public function testProcessCondition(): void
    {
        $dto = new GridRequestDto(
            [
                self::SEARCH => 'a',
            ],
        );

        $f = (new EntityFilter($this->em));
        $this->setProperty($f, 'filterCols', []);

        self::expectException(GridException::class);
        self::expectExceptionCode(GridException::SEARCH_COLS_ERROR);
        $f->getData($dto);
    }

    /**
     * @throws Exception
     */
    public function testCheckFilterColumn(): void
    {
        $dto = new GridRequestDto(
            [
                self::FILTER => [[['column' => 'a', 'operator' => 'b', 'value' => 'c']]],
            ],
        );

        self::expectException(GridException::class);
        self::expectExceptionCode(GridException::FILTER_COLS_ERROR);
        (new EntityFilter($this->em))->getData($dto);
    }

    /**
     * @throws Exception
     */
    public function testGetConditionEq(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'EQ',
                                'value'    => 'String 1',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testGetConditionEqNull(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'EQ',
                                'value'    => NULL,
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEmpty($result);
    }

    /**
     * @throws Exception
     */
    public function testGetConditionNeq(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 0',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 9',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 2',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 3',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 4',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 5',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 6',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 7',
                            ],
                        ],
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'NEQ',
                                'value'    => 'String 8',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testGetConditionStarts(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'STARTS',
                                'value'    => 'String 1',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testGetConditionEnds(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'ENDS',
                                'value'    => 'ing 1',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testGetConditionBetween(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'BETWEEN',
                                'value'    => 0,
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'BETWEEN',
                                'value'    => [1, 2],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('+ 1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('+ 1 day')->format(self::DATETIME),
                    'float'  => 2.2,
                    'id'     => $result[1]['id'],
                    'int'    => 2,
                    'string' => 'String 2',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testGetConditionNotBetween(): void
    {
        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 0,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 2,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 3,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 4,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 5,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 6,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 7,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 8,
                            ],
                        ],
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => 9,
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('+ 1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );

        $result = (new EntityFilter($this->em))->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'int',
                                'operator' => 'NBETWEEN',
                                'value'    => [0, 9],
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => TRUE,
                    'date'   => $this->today->modify('- 1 day')->format(self::DATETIME),
                    'float'  => 0.0,
                    'id'     => $result[0]['id'],
                    'int'    => 0,
                    'string' => 'String 0',
                ],
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('+ 9 day')->format(self::DATETIME),
                    'float'  => 9.9,
                    'id'     => $result[1]['id'],
                    'int'    => 9,
                    'string' => 'String 9',
                ],
            ],
            $result,
        );
    }

    /**
     * @throws Exception
     */
    public function testGetDataMissingCountQuery(): void
    {
        $f = $this->getMockBuilder(EntityFilter::class)
            ->onlyMethods(['configCustomCountQuery'])
            ->setConstructorArgs([$this->em])
            ->getMock();
        $f->method('configCustomCountQuery')->willReturn(NULL);
        $this->setProperty($f, 'em', $this->em);

        $result = $f->getData(
            new GridRequestDto(
                [
                    self::FILTER => [
                        [
                            [
                                'column'   => 'string',
                                'operator' => 'ENDS',
                                'value'    => 'ing 1',
                            ],
                        ],
                    ],
                ],
            ),
            ['date'],
        );
        self::assertEquals(
            [
                [
                    'bool'   => FALSE,
                    'date'   => $this->today->modify('1 day')->format(self::DATETIME),
                    'float'  => 1.1,
                    'id'     => $result[0]['id'],
                    'int'    => 1,
                    'string' => 'String 1',
                ],
            ],
            $result,
        );
    }

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
                    ->setDate(new DateTime(sprintf('today +%s day', $i), new DateTimeZone('UTC'))),
            );
        }

        $this->em->flush();
    }

}
