<?php

/**
 * Pagination test case
 *
 * @package   Test\Mvc\Libs\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Mvc\Libs\Utils;

use Slick\Mvc\Libs\Utils\Pagination;

/**
 * Pagination test case
 *
 * @package   Test\Mvc\Libs\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class PaginationTest extends \Codeception\TestCase\Test
{

    /**
     * Create default pagination
     * @test
     */
    public function createDefaultPagination()
    {
        $pag = new Pagination(['total' => 300]);
        $this->assertInstanceOf(
            'Slick\Mvc\Libs\Utils\Pagination',
            $pag->setRowsPerPage(10)
        );
        $this->assertEquals(30, $pag->pages);
        $this->assertEquals(1, $pag->current);
        $this->assertEquals(0, $pag->offset);
        $this->assertInstanceOf(
            'Slick\Mvc\Libs\Utils\Pagination',
            $pag->setCurrent('foo')
        );
        $pag->setCurrent(3);
        $this->assertEquals(20, $pag->offset);
    }

    /**
     * Using query params to change the behavior
     * @test
     */
    public function createWithQueryParams()
    {
        $_GET = [
            'rows' => 6, 'page' => 2, 'url' => 'posts',
            'extension' => 'html'
        ];
        $pag = new Pagination(['total' => 36]);

        $this->assertInstanceOf(
            'Slick\Mvc\Libs\Utils\Pagination',
            $pag->setTotal('not a number')
        );
        $this->assertEquals(36, $pag->total);
        $this->assertInstanceOf(
            'Slick\Mvc\Libs\Utils\Pagination',
            $pag->setRowsPerPage('not a number')
        );
        $this->assertEquals(6, $pag->rowsPerPage);
        $this->assertEquals(6, $pag->pages);
        $this->assertEquals(2, $pag->current);
        $this->assertEquals(6, $pag->offset);

        $this->assertEquals('?rows=6&page=4', $pag->pageUrl(4));
    }
}