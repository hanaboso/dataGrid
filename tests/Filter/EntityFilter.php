<?php declare(strict_types=1);

namespace DataGridTests\Filter;

use DataGridTests\Entity\Entity;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use Hanaboso\DataGrid\GridFilterAbstract;

/**
 * Class EntityFilter
 *
 * @package DataGridTests\Filter
 */
final class EntityFilter extends GridFilterAbstract
{

    /**
     * @return mixed[]
     */
    protected function filterCols(): array
    {
        return [
            'bool'          => 'e.bool',
            'custom_string' => 'e.string',
            'date'          => 'e.date',
            'float'         => 'e.float',
            'id'            => 'e.id',
            'int'           => 'e.int',
            'string'        => 'e.string',
        ];
    }

    /**
     * @return mixed[]
     */
    protected function orderCols(): array
    {
        return [
            'bool'   => 'e.bool',
            'date'   => 'e.date',
            'float'  => 'e.float',
            'id'     => 'e.id',
            'int'    => 'e.int',
            'string' => 'e.string',
        ];
    }

    /**
     * @return mixed[]
     */
    protected function searchableCols(): array
    {
        return [
            'string',
            'custom_string',
            'int',
            'float',
        ];
    }

    /**
     * @return bool
     */
    protected function useFetchJoin(): bool
    {
        return parent::useFetchJoin();
    }

    /**
     *
     */
    protected function prepareSearchQuery(): QueryBuilder
    {
        return $this
            ->getRepository()
            ->createQueryBuilder('e')
            ->select('e.id', 'e.string', 'e.int', 'e.float', 'e.bool', 'e.date');
    }

    /**
     * @return QueryBuilder|NULL
     */
    protected function configCustomCountQuery(): ?QueryBuilder
    {
        return $this
            ->getRepository()
            ->createQueryBuilder('e')
            ->select('COUNT(e.id)');
    }

    /**
     * @return mixed[]
     */
    protected function configFilterColsCallbacks(): array
    {
        return [
            'custom_string' => static function (
                QueryBuilder $qb,
                $value,
                $name,
                Composite $c,
                ?string $operator,
            ): void {
                $c->add(GridFilterAbstract::getCondition($qb, $name, $value, $operator));
            },
        ];
    }

    /**
     *
     */
    protected function setEntity(): void
    {
        $this->entity = Entity::class;
    }

}
