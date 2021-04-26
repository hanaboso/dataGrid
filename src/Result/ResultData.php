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
final class ResultData
{

    private const DATE_TIME = 'Y-m-d H:i:s';

    /**
     * @var bool
     */
    private bool $fetchJoinCollection = TRUE;

    /**
     * ResultData constructor.
     *
     * @param Query $query
     */
    public function __construct(private Query $query)
    {
    }

    /**
     * @param int     $hydrationMode
     * @param mixed[] $dateTimes
     *
     * @return mixed[]
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
     * @return mixed[]
     */
    private function getResult($hydrationMode = AbstractQuery::HYDRATE_OBJECT): array
    {
        $this->query->setHydrationMode($hydrationMode);
        $paginated = new Paginator($this->query, $this->fetchJoinCollection);
        $paginated->setUseOutputWalkers(FALSE);

        return $paginated->getQuery()->execute();
    }

}
