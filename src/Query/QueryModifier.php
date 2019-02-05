<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 13:09
 */

namespace Hanaboso\DataGrid\Query;

use Doctrine\ORM\QueryBuilder;
use Hanaboso\DataGrid\Exception\GridException;

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

    public const EQ      = 'EQ';
    public const NEQ     = 'NEQ';
    public const GT      = 'GT';
    public const LT      = 'LT';
    public const GTE     = 'GTE';
    public const LTE     = 'LTE';
    public const LIKE    = 'LIKE';
    public const STARTS  = 'STARTS';
    public const ENDS    = 'ENDS';
    public const FL      = 'FL';
    public const NFL     = 'NFL';
    public const BETWEEN = 'BETWEEN';

    /**
     * @param QueryBuilder $qb
     * @param array        $filters
     * @param array        $advancedFilters
     * @param array        $searchers
     * @param string       $searchValue
     *
     * @return QueryBuilder
     */
    public static function filter(
        QueryBuilder $qb,
        array $filters,
        array $advancedFilters,
        array $searchers,
        string $searchValue
    ): QueryBuilder
    {
        $i = 2;
        foreach ($filters as $key => $filter) {

            if ($filter instanceof FilterCallbackDto) {
                $expr = $qb->expr()->andX();
                call_user_func(
                    $filter->getCallback(),
                    $qb,
                    $filter->getValue(),
                    $filter->getColumnName(),
                    $expr,
                    NULL
                );

                $qb->andWhere($expr);
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

        $expr = $qb->expr()->andX();
        $adds = FALSE;
        foreach ($advancedFilters as $advancedFilter) {
            $adds   = TRUE;
            $orExpr = $qb->expr()->orX();
            foreach ($advancedFilter as $innerFilter) {
                $condition = NULL;
                if ($innerFilter['column'] instanceof FilterCallbackDto) {
                    call_user_func(
                        $innerFilter['column']->getCallback(),
                        $qb,
                        $innerFilter['column']->getValue(),
                        $innerFilter['column']->getColumnName(),
                        $orExpr,
                        $innerFilter['operation']
                    );
                } else {
                    $condition = self::getCondition(
                        $qb,
                        $innerFilter['column'],
                        $innerFilter['value'],
                        $innerFilter['operation']
                    );
                }
                if ($condition) {
                    $orExpr->add($condition);
                }
            }
            $expr->add($orExpr);
        }
        if ($adds) {
            $qb->andWhere($expr);
        }

        if ($searchValue) {
            $adds = FALSE;
            $expr = $qb->expr()->orX();
            foreach ($searchers as $s) {
                $adds = TRUE;
                if ($s instanceof FilterCallbackDto) {
                    call_user_func(
                        $s->getCallback(),
                        $qb,
                        $searchValue,
                        $s->getColumnName(),
                        $expr,
                        NULL
                    );
                } else {
                    $expr->add(self::getCondition($qb, $s, $searchValue, self::LIKE));
                }
            }

            if ($adds) {
                $qb->andWhere($expr);
            }
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
     * @param array $filter
     * @param array $filterCols
     * @param array $filterCallbacks
     *
     * @return array
     */
    public static function getAdvancedFilters(array $filter, array $filterCols, array $filterCallbacks): array
    {
        foreach ($filter as $index => $innerFilter) {
            $filter[$index] = self::getAdvancedInnerFilter($innerFilter, $filterCols, $filterCallbacks);
        }

        return $filter;
    }

    /**
     * @param array $order
     * @param array $cols
     *
     * @return string
     * @throws GridException
     */
    public static function getOrderString(array $order, array $cols): string
    {
        if (!isset($cols[$order[0]])) {
            throw  new GridException(
                sprintf('Column [%s] is not defined for sorting.', $order[0]),
                GridException::SORT_COLS_ERROR
            );
        }

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

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     * @param mixed        $value
     * @param string|null  $operator
     *
     * @return mixed|null
     */
    public static function getCondition(QueryBuilder $qb, string $name, $value, ?string $operator = NULL)
    {
        switch ($operator) {
            case self::EQ:
                return is_array($value) ?
                    $qb->expr()->in($name, self::getValue($value)) :
                    $qb->expr()->eq($name, self::getValue($value));
            case self::NEQ:
                return is_array($value) ?
                    $qb->expr()->notIn($name, self::getValue($value)) :
                    $qb->expr()->neq($name, self::getValue($value));
            case self::GT:
                return $qb->expr()->gt($name, self::getValue($value));
            case self::LT:
                return $qb->expr()->lt($name, self::getValue($value));
            case self::GTE:
                return $qb->expr()->gte($name, self::getValue($value));
            case self::LTE:
                return $qb->expr()->lte($name, self::getValue($value));
            case self::FL:
                $expr = $qb->expr()->orX();
                $expr->add($qb->expr()->isNotNull($name));
                $expr->add($qb->expr()->neq($name, sprintf("'%s'", $value)));

                return $expr;
            case self::NFL:
                $expr = $qb->expr()->orX();
                $expr->add($qb->expr()->isNull($name));
                $expr->add($qb->expr()->eq($name, sprintf("'%s'", $value)));

                return $expr;
            case self::LIKE:
                return $qb->expr()->like($name, sprintf("'%%%s%%'", $value));
            case self::STARTS:
                return $qb->expr()->like($name, sprintf("'%s%%'", $value));
            case self::ENDS:
                return $qb->expr()->like($name, sprintf("'%%%s'", $value));
            case self::BETWEEN:
                if (!is_array($value) || count($value) <= 1) {
                    return NULL;
                }

                return $qb->expr()->between($name, self::getValue($value[0]), self::getValue($value[1]));
        }

        return $qb->expr()->eq($name, self::getValue($value));
    }

    /**
     * @param array $filter
     * @param array $filterCols
     * @param array $filterCallbacks
     *
     * @return array
     */
    private static function getAdvancedInnerFilter(array $filter, array $filterCols, array $filterCallbacks): array
    {
        foreach ($filter as $index => $item) {
            $column = $item['column'] ?? ($item['name'] ?? '');

            if (array_key_exists($column, $filterCols)
                && array_key_exists('operation', $item)
                && (array_key_exists('value', $item) || in_array($item['operation'], [self::FL, self::NFL], TRUE))
            ) {
                if (!array_key_exists('value', $item)) {
                    $filter[$index]['value'] = '';
                    $item['value']           = '';
                }

                if (isset($filterCallbacks[$column])) {
                    $filter[$index]['column'] = new FilterCallbackDto(
                        $filterCallbacks[$column],
                        $item['value'],
                        $filterCols[$column]
                    );
                } else {
                    $filter[$index]['column'] = $filterCols[$column];
                }
            } else {
                unset($filter[$index]);
            }
        }

        return $filter;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private static function getValue($value)
    {
        if (is_numeric($value)) {
            return sprintf('%s', $value);
        } elseif (is_bool($value)) {
            return sprintf('%s', $value ? "true" : "false");
        } elseif (is_string($value)) {
            return sprintf('\'%s\'', $value);
        } elseif (is_null($value)) {
            return '\'\'';
        }

        return $value;
    }

}