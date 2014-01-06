<?php

/**
 * Service use case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di;

use Codeception\Util\Stub,
    Slick\Di\Service;

/**
 * Service use case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ServiceTest extends \Codeception\TestCase\Test
{

    /**
     * Cration and interface
     * @test
     */
    public function createService()
    {
        $service = new Service(array('name' => 'test'));
        $this->assertEquals('test', $service->getName());
        $this->assertFalse($service->isShared());
        $this->assertSame($service, $service->setShared(true));
        $this->assertTrue($service->getShared());
        $this->assertSame($service, $service->setDefinition('\StdClass'));
        $this->assertEquals('\StdClass', $service->getDefinition());
        $this->assertTrue(is_object($service->resolve()));
    }

    /**
     * Check callable resolution
     * @test
     */
    public function resolveCallable()
    {
        $service = new Service(
            array(
                'name' => 'test',
                'definition' => array('\Di\MyTestClass', 'runMe')
            )
        );
        $this->assertEquals("Test function", $service->resolve());
    }

    /**
     * Chec object resolution
     * @test
     */
    public function resolveObject()
    {
        $obj = new \StdClass();
        $obj->name = "Resolve Object";

        $service = new Service(
            array(
                'name' => 'test',
                'definition' => $obj
            )
        );
        $this->assertSame($obj, $service->resolve());
    }

    /**
     * Chec object closure
     * @test
     */
    public function resolveClosure()
    {
        $service = new Service(
            array(
                'name' => 'test',
                'definition' => function() {
                    return "Hello from closure";
                }
            )
        );
        $this->assertEquals("Hello from closure", $service->resolve());
    }

    /**
     * Check array resolve
     * @test
     */
    public function resolveArray()
    {
        $service = new Service(
            array(
                'name' => 'test',
                'definition' => array(
                    'className' => '\Di\MyTestClass',
                    'arguments' => array(
                        array('type' => 'parameter', 'value' => "Blue")
                    ),
                    'calls' => array(
                        array(
                            'method' => 'paint',
                            'arguments' => array(
                                array('type' => 'parameter', 'value' => 'Yellow')
                            )
                        ),
                        array(
                            'method' => 'dark'
                        )
                    ),
                    'properties' => array(
                        'back' => 'Brown'
                    )
                )

            )
        );
        $obj = $service->resolve();
        $this->assertInstanceOf('\Di\MyTestClass', $obj);
        $this->assertEquals("Dark Yellow", $obj->color);
        $this->assertEquals("Brown", $obj->back);
    }

}

/**
 * Test class for resolve method.
 */
class MyTestClass
{
    public $color = 'Red';

    public $back = 'Red';

    public function __construct($param = 'Green', $back = 'Red')
    {
        $this->color = $param;
        $this->back = $back;
    }

    public function dark()
    {
        $this->color  = 'Dark ' . $this->color;
    }

    public function paint($color)
    {
        $this->color = (string) $color;
    }

    public static function runMe()
    {
        return "Test function";
    }
}