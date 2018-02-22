<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 13:09
 */

namespace Hanaboso\DataGrid\Query;

use Doctrine\ORM\QueryBuilder;

/**
 * Class QueryModifier
 *
 * @package Hanaboso\DataGrid\Query
 */
class QueryModifier
{

    /**
     * key in array filter for fulltext search
     */
    public const FILTER_SEARCH_KEY = '_MODIFIER_SEARCH';

    /**
     * Value for filter when filter creates where `dbCol` IS NOT NULL
     */
    public const FILER_VAL_NOT_NULL = '_MODIFIER_VAL_NOT_NULL';

    /**
     * @param QueryBuilder $qb
     * @param array        $filters
     * @param array        $searchers
     * @param string       $searchValue
     *
     * @return QueryBuilder
     */
    public static function filter(
        QueryBuilder $qb,
        array $filters = [],
        array $searchers = [],
        string $searchValue
    ): QueryBuilder
    {
        $i = 2;
        foreach ($filters as $key => $filter) {

            if ($filter instanceof FilterCallbackDto) {
                call_user_func($filter->getCallback(), $qb, $filter->getValue(), $filter->getColumnName());
            } elseif (is_null($filter)) {
                $qb->andWhere($key . ' IS NULL');
            } elseif ($filter === self::FILER_VAL_NOT_NULL) {
                $qb->andWhere($key . ' IS NOT NULL');
            } elseif (is_array($filter)) {
                $uniqId = uniqid();
                $qb->andWhere($key . ' IN (:valsIN' . $uniqId . ')')->setParameter('valsIN' . $uniqId, $filter);
            } elseif (preg_match('/[><=]/', $key)) {
                $qb->andWhere($key . '?' . $i)->setParameter($i, $filter);
            } else {
                $qb->andWhere($key . '=?' . $i)->setParameter($i, $filter);
            }

            $i++;
        }

        if ($searchValue) {
            $array = [];
            foreach ($searchers as $s) {
                if ($s instanceof FilterCallbackDto) {
                    call_user_func($s->getCallback(), $qb, $searchValue, $s->getColumnName());
                    continue;
                }
                $array[] = sprintf('%s LIKE :search', $s);
            }

            $qb->andWhere(implode(' OR ', $array))->setParameter('search', sprintf('%%%s%%', $searchValue));
        }

        return $qb;
    }

    /**
     * @param array $filter
     * @param array $filterCols
     * @param array $filterCallbacks
     *
     * @return array
     */
    public static function getFilters(array $filter, array $filterCols, array $filterCallbacks): array
    {
        $filters = [];
        foreach ($filterCols as $name => $col) {
            if (array_key_exists($name, $filter)) {
                if (isset($filterCallbacks[$name])) {
                    $filters[$col] = new FilterCallbackDto($filterCallbacks[$name], $filter[$name], $col);
                } else {
                    $filters[$col] = $filter[$name];
                }
            }
        }

        return $filters;
    }

    /**
     * @param array $order
     * @param array $cols
     *
     * @return string
     */
    public static function getOrderString(array $order, array $cols): string
    {
        return sprintf('%s %s', $cols[$order[0]], $order[1]);
    }

    /**
     * @param array $filter
     *
     * @return string
     */
    public static function getSearch(array $filter): string
    {
        return $filter[self::FILTER_SEARCH_KEY] ?? '';
    }

}