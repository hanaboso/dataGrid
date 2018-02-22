<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 16:43
 */

namespace Hanaboso\DataGrid;

use Symfony\Component\HttpFoundation\Request;
use Hanaboso\DataGrid\Query\QueryModifier;

/**
 * Class GridResponseAbstract
 *
 * @package Hanaboso\DataGrid
 */
class GridRequestDto
{

    public const  LIMIT         = 'Limit';
    private const FILTER        = 'Filter';
    private const PAGE          = 'Page';
    private const TOTAL         = 'Total';
    private const ORDER_BY      = 'OrderBy';
    private const DEFAULT_LIMIT = 10;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var int
     */
    private $total = 0;

    /**
     * @var array
     */
    private $filter = [];

    /**
     * @var int
     */
    private $limit = 0;

    /**
     * DataGridParams constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getFilter(): array
    {
        if ($this->request->headers->has(self::FILTER)) {
            $filter = json_decode($this->request->headers->get(self::FILTER), TRUE);
            if (isset($filter['search'])) {
                $filter[QueryModifier::FILTER_SEARCH_KEY] = $filter['search'];
                unset($filter['search']);
            }

            return array_merge($filter, $this->filter);
        }

        return $this->filter;
    }

    /**
     * @param array $filter
     *
     * @return GridRequestDto
     */
    public function setAdditionalFilters(array $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return null|string|array
     */
    public function getPage()
    {
        if ($this->request->headers->has(self::PAGE)) {
            return $this->request->headers->get(self::PAGE);
        }

        return NULL;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        if ($this->limit !== 0) {
            return $this->limit;
        }

        if ($this->request->headers->has(self::LIMIT)) {
            return (int) $this->request->headers->get(self::LIMIT);
        }

        return self::DEFAULT_LIMIT;
    }

    /**
     * @param int $limit
     *
     * @return GridRequestDto
     */
    public function setLimit(int $limit): GridRequestDto
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return null|string|array
     */
    private function getOrderByForHeader()
    {
        if ($this->request->headers->has(self::ORDER_BY)) {
            return $this->request->headers->get(self::ORDER_BY);
        }

        return NULL;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        if ($this->request->headers->has(self::ORDER_BY) && $this->request->headers->get(self::ORDER_BY)) {

            preg_match('/[+-]/', $this->request->headers->get(self::ORDER_BY), $orderArray);

            if (reset($orderArray) == '+') {
                $order = 'ASC';
            } else {
                $order = 'DESC';
            }

            $columnName = preg_replace('/[+-]/', '', $this->request->headers->get(self::ORDER_BY));

            $arr = [$columnName, $order];

            return $arr;
        }

        return [];
    }

    /**
     * @param int $total
     *
     * @return GridRequestDto
     */
    public function setTotal(int $total): GridRequestDto
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return array
     */
    public function getParamsForHeader(): array
    {
        return [
            self::FILTER   => $this->formatFilterForHeader($this->getFilter()),
            self::PAGE     => $this->getPage(),
            self::LIMIT    => $this->getLimit(),
            self::TOTAL    => $this->total,
            self::ORDER_BY => $this->getOrderByForHeader(),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function formatFilterForHeader(array $data): array
    {
        foreach ($data as &$item) {
            if (is_array($item)) {
                $item = implode(',', $item);
            }
        }

        return $data;
    }

}