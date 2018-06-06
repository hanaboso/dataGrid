<?php declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: radekj
 * Date: 20.9.17
 * Time: 13:46
 */

namespace Hanaboso\DataGrid\Exception;

use Exception;

/**
 * Class GridException
 *
 * @package Hanaboso\DataGrid\Exception
 */
final class GridException extends Exception
{

    public const FETCH_ERROR              = 1;
    public const FETCH_ONE_ERROR          = 2;
    public const MODIFY_RESULT_DATA_ERROR = 3;
    public const GET_ITERATOR_ERROR       = 4;
    public const GET_TOTAL_COUNT_ERROR    = 5;
    public const SEARCH_COLS_ERROR        = 6;
    public const SEARCH_QUERY_NOT_FOUND   = 7;
    public const SORT_COLS_ERROR          = 8;

}