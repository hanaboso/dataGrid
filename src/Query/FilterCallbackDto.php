<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 13:22
 */

namespace Hanaboso\DataGrid\Query;

/**
 * Class FilterCallbackDto
 *
 * @package Hanaboso\DataGrid\Query
 */
final class FilterCallbackDto
{

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $columnName;

    /**
     * FilterCallbackDto constructor.
     *
     * @param callable $callback
     * @param mixed    $value
     * @param string   $columnName
     */
    public function __construct(callable $callback, $value, string $columnName)
    {
        $this->callback   = $callback;
        $this->value      = $value;
        $this->columnName = $columnName;
    }

    /**
     * @return callable callback
     */
    public function getCallback(): callable
    {
        return $this->callback;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

}