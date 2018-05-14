<?php declare(strict_types=1);

namespace Hanaboso\DataGrid;

/**
 * Interface GridRequestDtoInterface
 *
 * @package Hanaboso\DataGrid
 */
interface GridRequestDtoInterface
{

    /**
     * @return array
     */
    public function getFilter(): array;

    /**
     * @return null|string|array
     */
    public function getPage();

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @return array
     */
    public function getOrderBy(): array;

    /**
     * @param int $total
     *
     * @return GridRequestDtoInterface
     */
    public function setTotal(int $total): GridRequestDtoInterface;

}