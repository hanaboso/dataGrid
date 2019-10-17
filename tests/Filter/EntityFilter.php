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
     * @var string[]
     */
    protected $filterCols = [
        'id'            => 'e.id',
        'string'        => 'e.string',
        'int'           => 'e.int',
        'float'         => 'e.float',
        'bool'          => 'e.bool',
        'date'          => 'e.date',
        'custom_string' => 'e.string',
    ];

    /**
     * @var string[]
     */
    protected $orderCols = [
        'id'     => 'e.id',
        'string' => 'e.string',
        'int'    => 'e.int',
        'float'  => 'e.float',
        'bool'   => 'e.bool',
        'date'   => 'e.date',
    ];

    /**
     * @var string[]
     */
    protected $searchableCols = [
        'string',
        'int',
        'float',
    ];

    /**
     *
     */
    protected function prepareSearchQuery(): void
    {
        $this->searchQuery = $this
            ->getRepository()
            ->createQueryBuilder('e')
            ->select('e.id', 'e.string', 'e.int', 'e.float', 'e.bool', 'e.date');
    }

    /**
     *
     */
    protected function configCustomCountQuery(): void
    {
        parent::configCustomCountQuery();

        $this->countQuery = $this
            ->getRepository()
            ->createQueryBuilder('e')
            ->select('COUNT(e.id)');
    }

    /**
     *
     */
    protected function configFilterColsCallbacks(): void
    {
        parent::configFilterColsCallbacks();

        $this->filterColsCallbacks = [
            'custom_string' => function (QueryBuilder $qb, $value, $name, Composite $c, ?string $operator): void {
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
