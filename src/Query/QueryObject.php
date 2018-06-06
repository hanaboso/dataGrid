<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 14:40
 */

namespace Hanaboso\DataGrid\Query;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Hanaboso\DataGrid\Exception\GridException;
use Hanaboso\DataGrid\Result\ResultData;
use Iterator;

/**
 * Class QueryObject
 *
 * @package Hanaboso\DataGrid\Query
 */
class QueryObject
{

    /**
     * @var QueryBuilder
     */
    private $basicQueryBuilder;

    /**
     * @var QueryBuilder|NULL
     */
    private $countQueryBuilder = NULL;

    /**
     * @var Query
     */
    private $lastQuery;

    /**
     * @var ResultData
     */
    private $lastResult;

    /**
     * @var array
     */
    private $onPostFetch = [];

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $searches;

    /**
     * @var string
     */
    private $searchValue;

    /**
     * @var string
     */
    private $select;

    /**
     * @var bool
     */
    private $fetchJoin;

    /**
     * @var bool
     */
    private $useOutputWalkers;

    /**
     * @param array             $filters
     * @param array             $searches
     * @param string            $searchValue
     * @param QueryBuilder      $basicQuery
     * @param QueryBuilder|null $countQuery
     * @param bool              $fetchJoin
     * @param bool              $useOutputWalkers
     */
    public function __construct(
        array $filters,
        array $searches,
        string $searchValue,
        QueryBuilder $basicQuery,
        ?QueryBuilder $countQuery = NULL,
        bool $fetchJoin = TRUE,
        bool $useOutputWalkers = FALSE
    )
    {
        $this->filters           = $filters;
        $this->searches          = $searches;
        $this->searchValue       = $searchValue;
        $this->basicQueryBuilder = $basicQuery;
        $this->countQueryBuilder = $countQuery;
        $this->fetchJoin         = $fetchJoin;
        $this->useOutputWalkers  = $useOutputWalkers;
    }

    /**
     * @param EntityRepository $repository
     * @param ResultData|NULL  $resultSet
     * @param Paginator|NULL   $paginatedQuery
     *
     * @return float|int|mixed
     * @throws GridException
     * @throws NonUniqueResultException
     */
    public function count(
        EntityRepository $repository,
        ?ResultData $resultSet = NULL,
        ?Paginator $paginatedQuery = NULL
    )
    {
        if ($this->countQueryBuilder) {
            return $this->filterQuery($this->countQueryBuilder)->getQuery()->getSingleScalarResult();
        }

        if ($paginatedQuery) {
            return count($paginatedQuery->getQuery()->execute());
        }
        $query          = $this->getQuery($repository);
        $paginatedQuery = new Paginator($query, $resultSet ? $resultSet->getFetchJoinCollection() : TRUE);
        $paginatedQuery->setUseOutputWalkers($resultSet ? $resultSet->getUseOutputWalkers() : NULL);

        return $paginatedQuery->count();
    }

    /**
     * @param EntityRepository $repository
     * @param int              $hydrationMode
     *
     * @return array|ResultData
     * @throws GridException
     */
    public function fetch(EntityRepository $repository, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        $query = $this->getQuery($repository);

        if ($hydrationMode !== AbstractQuery::HYDRATE_OBJECT) {
            try {
                return $query->execute(NULL, $hydrationMode);
            } catch (Exception $e) {
                throw new GridException($e->getMessage(), GridException::FETCH_ERROR, $e);
            }
        }

        return $this->lastResult;
    }

    /**
     * @param EntityRepository $repository
     *
     * @return object
     * @throws GridException
     */
    public function fetchOne(EntityRepository $repository)
    {
        $query = $this->getQuery($repository)->setMaxResults(1);
        try {
            return $query->getSingleResult();
        } catch (Exception $e) {
            throw new GridException($e->getMessage(), GridException::FETCH_ONE_ERROR, $e);
        }
    }

    /**
     * Run all callbacks AQueryObject::onPostFetch[] after run AQueryObject::fetch()
     *
     * @param EntityRepository $repository
     * @param Iterator         $iterator
     */
    public function postFetch(EntityRepository $repository, Iterator $iterator): void
    {
        foreach ($this->onPostFetch as $callback) {
            call_user_func($callback, $repository, $iterator);
        }
    }

    /**
     * ---------------------------------------------- SETTERS ---------------------------------------------------
     */

    /**
     * @param string $cols
     *
     * @return QueryObject
     */
    public function select($cols): QueryObject
    {
        $this->select = $cols;

        return $this;
    }

    /**
     * ---------------------------------------------- HELPERS ---------------------------------------------------
     */

    /**
     * @param EntityRepository $repository
     *
     * @throws GridException
     * @return Query
     */
    private function getQuery(EntityRepository $repository): Query
    {
        $query = $this->filterQuery(clone $this->basicQueryBuilder)->getQuery();
        if ($this->lastQuery && $this->lastQuery->getDQL() === $query->getDQL()) {
            $query = $this->lastQuery;
        }
        if ($this->lastQuery !== $query) {
            $this->lastResult = new ResultData($query, $this, $repository);
            $this->lastResult->setFetchJoinCollection($this->fetchJoin);
            $this->lastResult->setUseOutputWalkers($this->useOutputWalkers);
        }

        return $this->lastQuery = $query;
    }

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    private function filterQuery($qb): QueryBuilder
    {
        $qb = QueryModifier::filter($qb, $this->filters, $this->searches, $this->searchValue);

        if ($this->select) {
            $qb = $qb->select($this->select);
        }

        return $qb;
    }

}