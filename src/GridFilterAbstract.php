<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 13:02
 */

namespace Hanaboso\DataGrid;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Hanaboso\DataGrid\Exception\GridException;
use Hanaboso\DataGrid\Query\FilterCallbackDto;
use Hanaboso\DataGrid\Query\QueryModifier;
use Hanaboso\DataGrid\Query\QueryObject;
use Hanaboso\DataGrid\Result\ResultData;

/**
 * Class GridFilterAbstract
 *
 * @package Hanaboso\DataGrid
 */
abstract class GridFilterAbstract
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var string
     */
    protected $search;

    /**
     * @var array
     */
    protected $filters;

    /**
     * @var string
     */
    protected $order;

    /**
     * @var array
     */
    protected $filterColsCallbacks = [];

    /**
     * @var array
     */
    protected $filterCols = [];

    /**
     * @var array
     */
    protected $orderCols = [];

    /**
     * @var array
     */
    protected $searchableCols = [];

    /**
     * @var QueryBuilder
     */
    protected $searchQuery;

    /**
     * @var array
     */
    protected $searchQueryParams;

    /**
     * @var QueryBuilder|NULL
     */
    protected $countQuery = NULL;

    /**
     * @var bool
     */
    protected $fetchJoin = TRUE;

    /**
     * @var int
     */
    private $whispererLimit = 50;

    /**
     * GridFilterAbstract constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->setEntity();
        $this->configFilterColsCallbacks();
        $this->prepareSearchQuery();
    }

    /**
     *
     */
    abstract protected function prepareSearchQuery(): void;

    /**
     *
     */
    abstract protected function setEntity(): void;

    /**
     * @param array $filter
     *
     * @return array
     * @throws GridException
     */
    public function getWhispererData(array $filter = []): array
    {
        $this->filters = QueryModifier::getFilters($filter, $this->filterCols, $this->filterColsCallbacks);
        $this->search  = QueryModifier::getSearch($filter);

        $arr = [];

        foreach ($this->searchableCols as $name => $col) {
            $object  = $this->getFilteredQuery([$col => $name])->select($name . ' AS ' . $col);
            $results = $this->getResultData($object);

            $i = 0;
            foreach ($results as $result) {
                if ($i > $this->whispererLimit) {
                    break;
                }
                $arr[] = $result[$col];
                $i++;
            }
        }

        return array_unique($arr);
    }

    /**
     * @param GridRequestDtoInterface $gridRequestDto
     *
     * @return ResultData|array
     * @throws GridException
     */
    public function getData(GridRequestDtoInterface $gridRequestDto)
    {
        if (!empty($this->searchQueryParams)) {
            $this->prepareSearchQuery();
        }

        $object = $this->getQuery($gridRequestDto->getFilter(), $gridRequestDto->getOrderBy());
        $data   = $this->getResultData($object);

        if (!empty($gridRequestDto->getOrderBy())) {
            $data->applySorting($this->order);
        }

        $data->applyPagination((int) $gridRequestDto->getPage(), $gridRequestDto->getLimit());

        $gridRequestDto->setTotal($data->getTotalCount());

        return $data;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return [
            'filter' => $this->filterCols,
            'search' => $this->searchableCols,
            'order'  => $this->orderCols,
        ];
    }

    /**
     * @return EntityRepository|ObjectRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository($this->entity);
    }

    /**
     * @param array $params
     */
    public function setSearchQueryParams(array $params): void
    {
        $this->searchQueryParams = $params;
    }

    /**
     * -------------------------------------------- HELPERS -----------------------------------------------
     */

    /**
     * In child can configure AFilteredSource::filterColsCallbacks
     * example child content
     * $this->filterColsCallbacks[ESomeEnumCols::CREATED_AT_FROM] = [$object,'applyCreatedAtFrom']
     *
     * function applySomeFilter(QueryBuilder $qb,$filterVal,$colName){}
     */
    protected function configFilterColsCallbacks(): void
    {
    }

    /**
     * @param array $filter
     * @param array $order
     *
     * @return QueryObject
     * @throws GridException
     */
    private function getQuery(array $filter = [], array $order = []): QueryObject
    {
        $this->search  = QueryModifier::getSearch($filter);
        $this->filters = QueryModifier::getFilters($filter, $this->filterCols, $this->filterColsCallbacks);

        if (!empty($order)) {
            $this->order = QueryModifier::getOrderString($order, $this->orderCols);
        }

        return $this->getFilteredQuery();
    }

    /**
     * @param array $cols
     *
     * @return QueryObject
     * @throws GridException
     */
    private function getFilteredQuery(array $cols = []): QueryObject
    {
        if (empty($cols)) {
            $cols = $this->getSearchCols();
        }

        foreach ($cols as $name => $col) {
            if (isset($this->filterColsCallbacks[$name])) {
                $cols[$name] = new FilterCallbackDto($this->filterColsCallbacks[$name], NULL, $col);
            }
        }

        return new QueryObject(
            $this->filters,
            $cols,
            $this->search,
            $this->getSearchQuery(),
            $this->countQuery,
            $this->fetchJoin
        );
    }

    /**
     * @return array
     * @throws GridException
     */
    private function getSearchCols(): array
    {
        $search_cols = [];
        foreach ($this->searchableCols as $col) {
            if (!isset($this->orderCols[$col])) {
                $class = get_called_class();
                throw new GridException(
                    sprintf(
                        'Key %s contained %s::typeCols is not defined in %s::orderCols. Add definition %s::orderCols[\'%s\'] = "some db field"',
                        $col, $class, $class, $class, $col
                    ),
                    GridException::SEARCH_COLS_ERROR
                );
            }
            $search_cols[$col] = $this->orderCols[$col];
        }

        return $search_cols;
    }

    /**
     * @return QueryBuilder
     * @throws GridException
     */
    private function getSearchQuery(): QueryBuilder
    {
        if (!$this->searchQuery) {
            $class = get_called_class();
            throw new GridException(
                sprintf('QueryBuilder is missing. Add definition %s::searchQuery = "some db field"', $class),
                GridException::SEARCH_QUERY_NOT_FOUND
            );
        }

        return $this->searchQuery;
    }

    /**
     * @param QueryObject $object
     *
     * @return ResultData|array
     * @throws GridException
     */
    private function getResultData(QueryObject $object)
    {
        return $object->fetch($this->getRepository(), AbstractQuery::HYDRATE_OBJECT);
    }

}
