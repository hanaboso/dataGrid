<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 13:57
 */

namespace Hanaboso\DataGrid\Result;

use ArrayIterator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Hanaboso\DataGrid\Exception\GridException;
use Hanaboso\DataGrid\Query\QueryObject;
use Nette\Utils\Strings;

/**
 * Class ResultData
 *
 * @package Hanaboso\DataGrid\Result
 */
class ResultData
{

    /**
     * @var Query
     */
    private $query;

    /**
     * @var QueryObject|NULL
     */
    private $queryObject;

    /**
     * @var EntityRepository|NULL
     */
    private $repository;

    /**
     * @var ArrayIterator|NULL
     */
    private $iterator;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @var bool
     */
    private $fetchJoinCollection = TRUE;

    /**
     * @var bool
     */
    private $useOutputWalkers = FALSE;

    /**
     * @var bool
     */
    private $frozen = FALSE;

    /**
     * ResultData constructor.
     *
     * @param Query                 $query
     * @param QueryObject|NULL      $queryObject
     * @param EntityRepository|NULL $repository
     */
    public function __construct(
        Query $query,
        ?QueryObject $queryObject = NULL,
        ?EntityRepository $repository = NULL
    )
    {
        $this->query       = $query;
        $this->queryObject = $queryObject;
        $this->repository  = $repository;
    }

    /**
     * @param bool $fetchJoinCollection
     *
     * @return ResultData
     * @throws GridException
     */
    public function setFetchJoinCollection(bool $fetchJoinCollection): ResultData
    {
        $this->updating();
        $this->fetchJoinCollection = $fetchJoinCollection;
        $this->iterator            = NULL;

        return $this;
    }

    /**
     * @param bool $useOutputWalkers
     *
     * @return ResultData
     * @throws GridException
     */
    public function setUseOutputWalkers(bool $useOutputWalkers): ResultData
    {
        $this->updating();
        $this->useOutputWalkers = $useOutputWalkers;
        $this->iterator         = NULL;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseOutputWalkers(): bool
    {
        return $this->useOutputWalkers;
    }

    /**
     * @return bool
     */
    public function getFetchJoinCollection(): bool
    {
        return $this->fetchJoinCollection;
    }

    /**
     * Removes ORDER BY clause that is not inside subquery.
     *
     * @return ResultData
     * @throws GridException
     */
    public function clearSorting(): ResultData
    {
        $this->updating();
        $dql = Strings::normalize($this->query->getDQL());

        // Removes everything from last 'ORDER BY' to end of a string
        if (preg_match('~^(.+)\\s+(ORDER BY\\s+((?!FROM|WHERE|ORDER\\s+BY|GROUP\\sBY|JOIN).)*)\\z~si', $dql, $m)) {
            $dql = $m[1];
        }
        $this->query->setDQL(trim($dql));

        return $this;
    }

    /**
     * @param int $page
     * @param int $itemsPerPage
     *
     * @return ResultData
     * @throws GridException
     */
    public function applyPagination(int $page = 1, int $itemsPerPage = 25): ResultData
    {
        $page = max(1, min($page, ceil($this->getTotalCount() / $itemsPerPage)));
        $this->query->setFirstResult(intval(--$page * $itemsPerPage));
        $this->query->setMaxResults($itemsPerPage);

        return $this;
    }

    /**
     * @return int
     * @throws GridException
     */
    public function getTotalCount(): int
    {
        if ($this->totalCount === NULL) {
            try {
                $this->frozen   = TRUE;
                $paginatedQuery = $this->createPaginatedQuery($this->query);
                if ($this->queryObject !== NULL && $this->repository !== NULL) {
                    $this->totalCount = (int) $this->queryObject->count($this->repository, $this, $paginatedQuery);
                } else {
                    $this->totalCount = $paginatedQuery->count();
                }
            } catch (Exception $e) {
                throw new GridException($e->getMessage(), GridException::GET_TOTAL_COUNT_ERROR, $e);
            }
        }

        return $this->totalCount;
    }

    /**
     * @param int $hydrationMode
     *
     * @return array
     * @throws GridException
     */
    public function toArray(int $hydrationMode = AbstractQuery::HYDRATE_OBJECT): array
    {
        return iterator_to_array(clone $this->getIterator($hydrationMode), FALSE);
    }

    /**
     * @return int
     * @throws GridException
     */
    public function count(): int
    {
        return $this->getIterator()->count();
    }

    /**
     * @return bool
     * @throws GridException
     */
    public function isEmpty(): bool
    {
        $count  = $this->getTotalCount();
        $offset = $this->query->getFirstResult();

        return $count <= $offset;
    }

    /**
     * @param string|array $columns
     *
     * @return ResultData
     * @throws GridException
     */
    public function applySorting($columns): ResultData
    {
        $this->clearSorting();
        $sorting = [];
        foreach (is_array($columns) ? $columns : func_get_args() as $name => $column) {
            $newColumn = NULL;
            if (!is_numeric($name)) {
                $newColumn = sprintf('%s %s', $name, $column);
            }
            $newColumn = trim(is_null($newColumn) ? $column : $newColumn);
            if (!preg_match('~\s+(DESC|ASC)\s*\z~i', $newColumn)
            ) {
                $newColumn .= ' ASC';
            }
            $sorting[] = $newColumn;
        }
        if ($sorting) {
            $dql = Strings::normalize($this->query->getDQL());
            if (!preg_match('~ORDER BY(?! .+\..+ SEPARATOR)~si', $dql, $m)) {
                $dql .= ' ORDER BY ';
            } else {
                $dql .= ', ';
            }
            $this->query->setDQL(sprintf('%s%s', $dql, implode(', ', $sorting)));
        }
        $this->iterator = NULL;

        return $this;
    }

    /**
     * -------------------------------------------- HELPERS ----------------------------------------
     */

    /**
     * @param int $hydrationMode
     *
     * @return ArrayIterator
     * @throws GridException
     */
    private function getIterator($hydrationMode = AbstractQuery::HYDRATE_OBJECT): ArrayIterator
    {
        if ($this->iterator !== NULL) {
            return $this->iterator;
        }
        $this->query->setHydrationMode($hydrationMode);
        try {
            $this->frozen = TRUE;
            if ($this->fetchJoinCollection && ($this->query->getMaxResults() > 0 || $this->query->getFirstResult() > 0)
            ) {
                $this->iterator = $this->createPaginatedQuery($this->query)->getIterator();
            } else {
                $this->iterator = new ArrayIterator($this->query->getResult(AbstractQuery::HYDRATE_OBJECT));
            }
            if ($this->queryObject !== NULL && $this->repository !== NULL) {
                $this->queryObject->postFetch($this->repository, $this->iterator);
            }

            return $this->iterator;
        } catch (Exception $e) {
            throw new GridException($e->getMessage(), GridException::GET_ITERATOR_ERROR, $e);
        }
    }

    /**
     * @param Query $query
     *
     * @return Paginator
     */
    private function createPaginatedQuery(Query $query): Paginator
    {
        $paginated = new Paginator($query, $this->fetchJoinCollection);
        $paginated->setUseOutputWalkers($this->useOutputWalkers);

        return $paginated;
    }

    /**
     * @throws GridException
     */
    private function updating(): void
    {
        if ($this->frozen !== FALSE) {
            throw new GridException(
                'Cannot modify result set, that was already fetched from storage.',
                GridException::MODIFY_RESULT_DATA_ERROR
            );
        }
    }

}
