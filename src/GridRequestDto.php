<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 16:43
 */

namespace Hanaboso\DataGrid;

use Hanaboso\DataGrid\Query\QueryModifier;
use Throwable;

/**
 * Class GridResponseAbstract
 *
 * @package Hanaboso\DataGrid
 */
class GridRequestDto implements GridRequestDtoInterface
{

    public const  LIMIT         = 'limit';
    private const FILTER        = 'filter';
    private const PAGE          = 'page';
    private const TOTAL         = 'total';
    private const ORDER_BY      = 'orderby';
    private const DEFAULT_LIMIT = 10;
    private const SEARCH        = 'search';

    /**
     * @var array
     */
    private $headers;

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
     * GridRequestDto constructor.
     *
     * @param array $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = array_change_key_case($headers, CASE_LOWER);
    }

    /**
     * @return array
     */
    public function getFilter(): array
    {
        if (array_key_exists(self::FILTER, $this->headers)) {
            $filter = json_decode($this->getHeader(self::FILTER), TRUE);
            if (isset($filter[self::SEARCH])) {
                $filter[QueryModifier::FILTER_SEARCH_KEY] = $filter[self::SEARCH];
                unset($filter[self::SEARCH]);
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
        $this->filter = array_merge($this->filter, $filter);

        return $this;
    }

    /**
     * @return null|string|array
     */
    public function getPage()
    {
        if (array_key_exists(self::PAGE, $this->headers)) {
            return $this->getHeader(self::PAGE);
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

        if (array_key_exists(self::LIMIT, $this->headers)) {
            return (int) $this->getHeader(self::LIMIT);
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
        if (array_key_exists(self::ORDER_BY, $this->headers)) {
            return $this->getHeader(self::ORDER_BY);
        }

        return NULL;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        if (array_key_exists(self::ORDER_BY, $this->headers) && $this->getHeader(self::ORDER_BY)) {

            preg_match('/[+-]/', $this->getHeader(self::ORDER_BY), $orderArray);

            if (reset($orderArray) == '+') {
                $order = 'ASC';
            } else {
                $order = 'DESC';
            }

            $columnName = preg_replace('/[+-]/', '', $this->getHeader(self::ORDER_BY));

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
    public function setTotal(int $total): GridRequestDtoInterface
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
        foreach ($data as $key => &$item) {
            if (is_array($item)) {
                try {
                    $item = implode(',', $item);
                } catch (Throwable $t) {
                    $item = '';
                }
            }

            if ($key === QueryModifier::FILTER_SEARCH_KEY) {
                $data[self::SEARCH] = $item;
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getHeader(string $key)
    {
        if (is_array($this->headers[$key])) {
            return $this->headers[$key][0] ?? '';
        } else {
            return $this->headers[$key];
        }
    }

}