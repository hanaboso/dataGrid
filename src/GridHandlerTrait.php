<?php declare(strict_types=1);

namespace Hanaboso\DataGrid;

/**
 * Trait GridHandlerTrait
 *
 * @package Hanaboso\DataGrid
 */
trait GridHandlerTrait
{

    /**
     * @param GridRequestDtoInterface $dto
     * @param array                   $items
     *
     * @return array
     */
    protected function getGridResponse(GridRequestDtoInterface $dto, array $items): array
    {
        $total    = $dto->getTotal();
        $page     = $dto->getPage();
        $lastPage = intval($dto->getTotal() / $dto->getItemsPerPage()) + 1;

        return [
            'items'  => $items,
            'filter' => $dto->getFilter(FALSE),
            'sorter' => $dto->getOrderBy(),
            'paging' => [
                'page'         => $page,
                'itemsPerPage' => $dto->getItemsPerPage(),
                'total'        => $total,
                'nextPage'     => min($lastPage, $page + 1),
                'lastPage'     => $lastPage,
                'previousPage' => max(1, $page - 1),
            ],
        ];
    }

}