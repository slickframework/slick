<?php

/**
 * Definition manager test case
 *
 * @package   Test\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Di\Definition;

use Codeception\Util\Stub;
use Slick\Di\Definition\DefinitionManager;
use Slick\Di\Definition\Scope;
use Slick\Di\DefinitionInterface;

/**
 * Definition manager test case
 *
 * @package   Test\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionManagerTest extends \Codeception\TestCase\Test
{

    /**
     * Use the definition manager
     * @test
     */
    public function manageDefinitions()
    {
        $manager = new DefinitionManager();
        $this->assertNull($manager->get('test'));
        $this->assertFalse($manager->has('test'));

        $definition = new MyDefinition();
        $this->assertInstanceOf('\Slick\Di\Definition\DefinitionManager', $manager->add($definition));
        $this->assertTrue($manager->has('test'));
        $this->assertSame($definition, $manager->get('test'));

    }
}

class MyDefinition implements DefinitionInterface
{

    /**
     * Returns the name of the entry in the container
     *
     * @return string
     */
    public function getName()
    {
        return 'test';
    }

    /**
     * Returns the scope of the entry
     *
     * @return Scope
     */
    public function getScope()
    {
        return Scope::SINGLETON();
    }
}