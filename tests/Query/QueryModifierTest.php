<?php declare(strict_types=1);

namespace Tests\Unit\Utils\Query;

use Doctrine\ORM\QueryBuilder;
use Tests\KernelTestCaseAbstract;
use Hanaboso\DataGrid\Query\FilterCallbackDto;
use Hanaboso\DataGrid\Query\QueryModifier;

/**
 * Class QueryModifierTest
 *
 * @package Tests\Unit\Utils\Query
 */
class QueryModifierTest extends KernelTestCaseAbstract
{

    /**
     * @cover QueryModifier::filter()
     */
    public function testFilter(): void
    {
        $call = new FilterCallbackDto(function ($q, $s, $col): void {
            $q->addSelect($s);
        }, 'qwe', 'asd');

        $qb     = new QueryBuilder($this->em);
        $filter = [
            'email' => NULL,
            'asd'   => QueryModifier::FILER_VAL_NOT_NULL,
            'num'   => [0, 1],
            'asd>=' => 5,
            'tr'    => 'sd',
            'c'     => $call,
        ];
        $search = ['email', 'asd', 'tr', $call];
        $val    = 'val';

        QueryModifier::filter($qb, $filter, $search, $val)->getQuery()->getDQL();
    }

    /**
     * @cover QueryModifier::getFilters
     */
    public function testGetFilters(): void
    {
        $filter      = ['emlFilter' => 'emil', 'pwdFilter' => 'pwws'];
        $filterCols  = ['emlFilter' => 'email', 'pwdFilter' => 'pwd'];
        $filterCalls = [
            'pwdFilter' => function (): void {
            },
        ];

        $res = QueryModifier::getFilters($filter, $filterCols, $filterCalls);
        self::assertTrue(isset($res['email']));
        self::assertEquals('emil', $res['email']);
        self::assertInstanceOf(FilterCallbackDto::class, $res{'pwd'});
        self::assertEquals('pwws', $res{'pwd'}->getValue());
        self::assertEquals('pwd', $res{'pwd'}->getColumnName());
    }

    /**
     * @cover QueryModifier::getOrderString
     */
    public function testGetOrderString(): void
    {
        self::assertEquals('u.name ASC',
            QueryModifier::getOrderString(['name', 'ASC'], ['eml' => 'u.eml', 'pwd' => 's.pwd', 'name' => 'u.name']));
    }

    /**
     * @cover QueryModifier::getSearch
     */
    public function testGetSearch(): void
    {
        self::assertEquals('', QueryModifier::getSearch([]));
        self::assertEquals('', QueryModifier::getSearch(['key' => 'key']));
        self::assertEquals('key', QueryModifier::getSearch([QueryModifier::FILTER_SEARCH_KEY => 'key']));
    }

}