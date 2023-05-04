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
     * @param mixed[]                 $items
     *
     * @return mixed[]
     */
    protected function getGridResponse(GridRequestDtoInterface $dto, array $items): array
    {
        $total    = $dto->getTotal();
        $page     = $dto->getPage();
        $lastPage = intval($dto->getTotal() / $dto->getItemsPerPage()) + 1;

        return [
            'filter' => $dto->getFilter(FALSE),
            'items'  => $items,
            'paging' => [
                'itemsPerPage' => $dto->getItemsPerPage(),
                'lastPage'     => $lastPage,
                'nextPage'     => min($lastPage, $page + 1),
                'page'         => $page,
                'previousPage' => max(1, $page - 1),
                'total'        => $total,
            ],
            'sorter' => $dto->getOrderBy(),
        ];
    }

}
