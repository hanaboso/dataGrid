<?php declare(strict_types=1);

namespace Hanaboso\DataGrid;

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

        return $filter;
    }

    /**
     * @param array $filter
     *
     * @return GridRequestDto
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
            return (int) ($this->headers[self::PAGING][self::PAGE] ?? 1);
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
            return (int) ($this->headers[self::PAGING][self::ITEMS_PER_PAGE] ?? self::DEFAULT_LIMIT);
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
     */
    public function getOrderBy(): array
    {
        if (array_key_exists(self::SORTER, $this->headers)) {
            return $this->headers[self::SORTER] ?: [];
        }

        return [];
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
