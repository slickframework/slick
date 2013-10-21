<?php

/**
 * Events test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Common;

use Slick\Common\Events;

/**
 * Events test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class EventsTest extends \Codeception\TestCase\Test
{

    /**
     * @var string Used int the callback.
     */
    public static $text = 'invalid';


    /**
     * Register, call and remove events
     * @test
     */
    public function registerAndCallEvents()
    {
        $callback = function($txt){
            EventsTest::$text = $txt;
        };
        Events::add('my.event', $callback);
        Events::fire('my.event', array('example'));
        $this->assertEquals('example', self::$text);
        Events::remove('my.event', $callback);
        Events::fire('my.event', array('another example'));
        $this->assertEquals('example', self::$text);
    }

}
