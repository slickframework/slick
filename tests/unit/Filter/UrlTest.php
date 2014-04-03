<?php

/**
 * URL filter test case
 *
 * @package    Test\Filter
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Filter;

use Slick\Filter\Url;

/**
 * URL filter test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class UrlTest extends \Codeception\TestCase\Test
{

    /**
     * Filter url
     * @test
     */
    public function filterUrl()
    {
        $url = "some silly url/index.html";
        $urlFilter = new Url();
        $this->assertEquals('somesillyurl/index.html', $urlFilter->filter($url));
    }

}