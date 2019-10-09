<?php declare(strict_types=1);

namespace Hanaboso\DataGrid\Result;

use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
     */
    public function toArray(int $hydrationMode = AbstractQuery::HYDRATE_OBJECT, array $dateTimes = []): array
    {
        $data = $this->getResult($hydrationMode);

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
     * @return array
     */
    private function getResult($hydrationMode = AbstractQuery::HYDRATE_OBJECT): array
    {
        $this->query->setHydrationMode($hydrationMode);
        $paginated = new Paginator($this->query, $this->fetchJoinCollection);
        $paginated->setUseOutputWalkers(FALSE);

        return $paginated->getQuery()->execute();
    }

}
