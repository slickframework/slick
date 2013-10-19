<?php

/**
 * ArrayMethods test case
 * 
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Utility;
use Slick\Utility\ArrayMethods;

/**
 * ArrayMethod test case
 * 
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class ArrayMethodsTest extends \Codeception\TestCase\Test
{
   
    /**
     * Try to clean a dirty array
     * 
     * @test
     */
    public function cleanAnArray()
    {
        $expected = array('one', 'two', 'three');
        $dirty = array_merge($expected, array('', ''));
        $this->assertEquals($expected, ArrayMethods::clean($dirty));
    }
    
    /**
     * Try to trim a dirty array
     * 
     * @test
     */
    public function trimAnArray()
    {
        $expected = array('one', 'two', 'three');
        $dirty = array('one ', ' two ', ' three');
        $this->assertEquals($expected, ArrayMethods::trim($dirty));
    }

}