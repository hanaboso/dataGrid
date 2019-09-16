<?php declare(strict_types=1);

namespace Hanaboso\DataGrid\Result;

use ArrayIterator;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Hanaboso\DataGrid\Exception\GridException;

/**
 * Class ResultData
 *
 * @package Hanaboso\DataGrid\Result
 */
class ResultData
{

    private const DATE_TIME = 'Y-m-d H:i:s';

    /**
     * @var Query
     */
    private $query;

    /**
     * @var ArrayIterator|NULL
     */
    private $iterator;

    /**
     * @var bool
     */
    private $fetchJoinCollection = TRUE;

    /**
     * ResultData constructor.
     *
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * @param int   $hydrationMode
     * @param array $dateTimes
     *
     * @return array
     * @throws GridException
     */
    public function toArray(int $hydrationMode = AbstractQuery::HYDRATE_OBJECT, array $dateTimes = []): array
    {
        $data = iterator_to_array(clone $this->getIterator($hydrationMode), FALSE);

        if ($dateTimes) {
            foreach ($data as $key => $item) {
                foreach ($dateTimes as $dateTime) {
                    if (isset($item[$dateTime]) && get_class($item[$dateTime]) === DateTime::class) {
                        $data[$key][$dateTime] = $data[$key][$dateTime]->format(self::DATE_TIME);
                    }
                }
            }
        }

        return $data;
    }

    /**
     * -------------------------------------------- HELPERS ----------------------------------------
     */

    /**
     * @param int $hydrationMode
     *
     * @return ArrayIterator
     * @throws GridException
     */
    private function getIterator($hydrationMode = AbstractQuery::HYDRATE_OBJECT): ArrayIterator
    {
        if ($this->iterator !== NULL) {
            return $this->iterator;
        }
        $this->query->setHydrationMode($hydrationMode);
        try {
            if ($this->fetchJoinCollection && ($this->query->getMaxResults() > 0 || $this->query->getFirstResult() > 0)
            ) {
                $this->iterator = $this->createPaginatedQuery($this->query)->getIterator();
            } else {
                $this->iterator = new ArrayIterator($this->query->getResult(AbstractQuery::HYDRATE_OBJECT));
            }

            return $this->iterator;
        } catch (Exception $e) {
            throw new GridException($e->getMessage(), GridException::GET_ITERATOR_ERROR, $e);
        }
    }

    /**
     * @param Query $query
     *
     * @return Paginator
     */
    private function createPaginatedQuery(Query $query): Paginator
    {
        $paginated = new Paginator($query, $this->fetchJoinCollection);
        $paginated->setUseOutputWalkers(FALSE);

        return $paginated;
    }

}
