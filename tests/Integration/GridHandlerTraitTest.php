<?php declare(strict_types=1);

namespace DataGridTests\Integration;

use DataGridTests\TestCaseAbstract;
use DataGridTests\TestClass\TestClass;
use Exception;
use Hanaboso\DataGrid\GridRequestDto;

/**
 * Class GridHandlerTraitTest
 *
 * @package DataGridTests\Integration
 */
final class GridHandlerTraitTest extends TestCaseAbstract
{

    protected const DATABASE = 'datagrid2';

    /**
     * @throws Exception
     */
    public function testGetGridResponse(): void
    {
        $dto = new GridRequestDto([]);
        $c   = new TestClass();

        $a = $this->invokeMethod($c, 'getGridResponse', [$dto, []]);
        self::assertNotEmpty($a);
    }

}

