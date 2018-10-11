<?php declare(strict_types=1);

namespace Tests\Filter;

use Doctrine\ORM\QueryBuilder;
use Hanaboso\DataGrid\GridFilterAbstract;
use Tests\Entity\Entity;

/**
 * Class EntityFilter
 *
 * @package Tests\Filter
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
        'int_gte'       => 'e.int>=',
        'int_gt'        => 'e.int>',
        'int_lt'        => 'e.int<',
        'int_lte'       => 'e.int<=',
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
            ->select('e.id', 'e.string', 'e.int', 'e.float', 'e.bool', "DATE_FORMAT(e.date, '%Y-%m-%d %H:%i:%s') date");
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
            'custom_string' => function (QueryBuilder $builder, string $value, string $name): void {
                $builder->where(sprintf('%s = :customString', $name))->setParameter('customString', $value);
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