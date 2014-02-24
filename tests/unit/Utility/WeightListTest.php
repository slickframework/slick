<?php

/**
 * WeightList test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Utility;

use Slick\Utility\WeightList;

/**
 * WeightList test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class WeightListTest extends \Codeception\TestCase\Test
{

    /**
     * Create a weight list
     * @test
     */
    public function createAWeightList()
    {
        $list = new WeightList();
        $this->assertTrue($list->isEmpty());
        $list->insert('one');
        $this->assertFalse($list->isEmpty());
        $this->assertEquals('one', $list->current());
        $this->assertEquals(0, $list->weight());

        $list->insert('three', 20);
        $this->assertInstanceOf('Slick\Utility\WeightList', $list->next());
        $this->assertEquals(1, $list->key());

        $list->insert('two', 20);
        $expected = ['one', 'two', 'three'];
        $this->assertEquals($expected, $list->asArray());
    }
}