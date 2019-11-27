<?php declare(strict_types=1);

namespace Hanaboso\DataGrid;

use Hanaboso\DataGrid\Exception\GridException;

/**
 * Class GridRequestDto
 *
 * @package Hanaboso\DataGrid
 */
class GridRequestDto implements GridRequestDtoInterface
{

    public const  ITEMS          = 'items';
    public const  ITEMS_PER_PAGE = 'itemsPerPage';
    public const  FILTER         = 'filter';
    public const  PAGE           = 'page';
    public const  PAGING         = 'paging';
    public const  TOTAL          = 'total';
    public const  SORTER         = 'sorter';
    public const  SEARCH         = 'search';
    private const DEFAULT_LIMIT  = 10;

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
    private $itemsPerPage = 0;

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
     * @param bool $withAdditional
     *
     * @return array
     * @throws GridException
     */
    public function getFilter(bool $withAdditional = TRUE): array
    {
        $filter = [];

        if (array_key_exists(self::FILTER, $this->headers)) {
            $filter = $this->headers[self::FILTER] ?: [];
        }

        if ($withAdditional) {
            return array_merge($filter, $this->filter);
        }

        foreach ($filter as $row) {
            if (!is_array($row)) {
                throw new GridException('Incorrect filter format - must be two nested arrays');
            }

            foreach ($row as $item) {
                if (!array_key_exists(GridFilterAbstract::COLUMN, $item)
                    || !array_key_exists(GridFilterAbstract::OPERATOR, $item)) {
                    throw new GridException(
                        sprintf(
                            '[%s, %s] filter fields are mandatory',
                            GridFilterAbstract::OPERATOR,
                            GridFilterAbstract::COLUMN
                        )
                    );
                }
            }
        }

        return $filter;
    }

    /**
     * @param array $filter
     *
     * @return GridRequestDto
     * @throws GridException
     */
    public function setAdditionalFilters(array $filter): self
    {
        $this->filter = array_merge($this->getFilter(), $filter);

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        if (array_key_exists(self::PAGING, $this->headers)) {
            return max((int) ($this->headers[self::PAGING][self::PAGE] ?? 1), 1);
        }

        return 1;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        if ($this->itemsPerPage !== 0) {
            return $this->itemsPerPage;
        }

        if (array_key_exists(self::PAGING, $this->headers)) {
            $limit = (int) ($this->headers[self::PAGING][self::ITEMS_PER_PAGE] ?? self::DEFAULT_LIMIT);

            return $limit > 0 ? $limit : self::DEFAULT_LIMIT;
        }

        return self::DEFAULT_LIMIT;
    }

    /**
     * @param int $itemsPerPage
     *
     * @return GridRequestDto
     */
    public function setItemsPerPage(int $itemsPerPage): GridRequestDto
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * @return null|string|array
     */
    private function getOrderByForHeader()
    {
        if (array_key_exists(self::SORTER, $this->headers)) {
            return json_encode($this->headers[self::SORTER], JSON_THROW_ON_ERROR) ?: '';
        }

        return NULL;
    }

    /**
     * @return array
     * @throws GridException
     */
    public function getOrderBy(): array
    {
        $sort = [];
        if (array_key_exists(self::SORTER, $this->headers)) {
            $sort = $this->headers[self::SORTER] ?: [];
        }

        foreach ($sort as $item) {
            if (!array_key_exists(GridFilterAbstract::COLUMN, $item)
                || !array_key_exists(GridFilterAbstract::DIRECTION, $item)) {
                throw new GridException(
                    sprintf(
                        'Each sorter must contain [%s, %s] keys',
                        GridFilterAbstract::COLUMN,
                        GridFilterAbstract::DIRECTION
                    )
                );
            }

            if (!in_array(
                $item[GridFilterAbstract::DIRECTION],
                [
                    GridFilterAbstract::ASCENDING,
                    GridFilterAbstract::DESCENDING,
                ]
            )) {
                throw new GridException(
                    sprintf(
                        'Invalid direction of sorter [%s], valid options: [%s, %s]',
                        $item[GridFilterAbstract::DIRECTION],
                        GridFilterAbstract::ASCENDING,
                        GridFilterAbstract::DESCENDING
                    )
                );
            }
        }

        return $sort;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     *
     * @return GridRequestDtoInterface
     */
    public function setTotal(int $total): GridRequestDtoInterface
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return array
     * @throws GridException
     */
    public function getParamsForHeader(): array
    {
        return [
            self::FILTER         => $this->formatFilterForHeader($this->getFilter()),
            self::PAGE           => $this->getPage(),
            self::ITEMS_PER_PAGE => $this->getItemsPerPage(),
            self::TOTAL          => $this->total,
            self::SEARCH         => $this->getSearch(),
            self::SORTER         => $this->getOrderByForHeader(),
        ];
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->headers[self::SEARCH] ?? NULL;
    }

    /**
     * @param array $data
     *
     * @return string|null
     */
    protected function formatFilterForHeader(array $data): ?string
    {
        return json_encode($data, JSON_THROW_ON_ERROR) ?: NULL;
    }

}
