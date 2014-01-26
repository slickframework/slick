<?php

/**
 * Paginator test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Utility;

use Codeception\Util\Stub;
use Slick\Utility\Paginator;

/**
 * Paginator test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class PaginatorTest extends \Codeception\TestCase\Test
{

    /**
     * Check the default values for paginator
     * @test
     */
    public function checkDefaultValues()
    {
        $paginator = new Paginator(array('totalRows' => 200));
        $this->assertEquals(17, $paginator->getPages());
        $this->assertEquals(1, $paginator->getCurrentPage());
        $this->assertEquals(0, $paginator->getOffset());
        $this->assertEquals(12, $paginator->getRowsPerPage());
    }

}