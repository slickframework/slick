<?php

/**
 * Container use case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di;

use Codeception\Util\Stub,
    Slick\Di\DependencyInjector,
    Slick\Di\DiAwareInterface,
    Slick\Common\Base;

/**
 * Container use case
 *
 * @package   Test\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ContainerTest extends \Codeception\TestCase\Test
{
    
    /**
     * Create a dependecy injector
     * @test
     */
    public function createInjector()
    {
        $di = new DependencyInjector();
        $this->assertInstanceOf("Slick\Di\DiInterface", $di);
        $di['test'] = function() {
            return "Hello test"; 
        };
        $this->assertTrue($di->has('test'));
        $this->assertTrue($di->offsetExists('test'));
        $this->assertEquals('Hello test', $di->get('test'));
        $this->assertEquals('Hello test', $di['test']);
        $this->assertFalse($di->wasFreshInstance());
        $this->assertFalse($di->isFreshInstance());
        unset($di['test']);
        $this->assertFalse(isset($di['test']));
    }

    /**
     * Retrieve a chared request
     * @test
     * @expectedException Slick\Di\Exception\ServiceNotFoundException
     */
    public function sharedRequest()
    {
        $di = new DependencyInjector();
        $di->setShared('testShared', 'Di\ObjectForDi');
        $obj1 = $di->get('testShared');
        $obj2 = $di->get('testShared');
        $this->assertSame($obj2, $obj1);

        $di->set('notShared', 'Di\ObjectForDi');
        $obj1 = $di->getShared('notShared');
        $obj2 = $di->getShared('notShared');
        $this->assertSame($obj2, $obj1);
        $obj3 = $di->get('notShared');
        $this->assertNotSame($obj2, $obj3);
        $di->get("foo");
    }

    /**
     * Attempt to creaate a servive if it not exists
     * @test
     */
    public function attemptService()
    {
        $di = new DependencyInjector();
        $srv1 = $di->attempt('notShared', 'Di\ObjectForDi');
        $this->assertSame($srv1, $di->attempt('notShared', 'Di\ObjectForDi'));

        $obj1 = $di->get('notShared');
        $this->assertInstanceOf("Slick\Di\DiInterface", $obj1->getDi());
        $this->assertInstanceOf("Slick\Di\DependencyInjector", $obj1->getDi());
        $this->assertEquals($di, $obj1->getDi());
    }
}

class ObjectForDi extends base implements DiAwareInterface
{

}