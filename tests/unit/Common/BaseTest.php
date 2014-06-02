<?php

/**
 * Base test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Common;

use Common\Examples\Animal;

/**
 * Use example Car class for tests.
 */
require_once dirname(__FILE__) . '/Examples/Animal.php';
require_once dirname(__FILE__) . '/Examples/Car.php';

/**
 * Base class test case
 * 
 * @package    Test\Common
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class BaseTest extends \Codeception\TestCase\Test
{

    /**
     * Create an object using an array and an object.
     * @test
     */
    public function createAnObject()
    {
        $animal = new Animal(array('name' => 'Cat', 'pet' => true));
        $this->assertInstanceOf('\Slick\Common\Base', $animal);
        $this->assertTrue($animal->isPet());

        $data = new \stdClass();
        $data->name = 'dog';
        $dog = new Animal($data);
        $this->assertInstanceOf('\Slick\Common\Base', $dog);
        $this->assertEquals('dog', $dog->getName());
        unset($animal, $data, $dog);
    }

    /**
     * Test the __wakeUp and __sleep methods.
     * @test
     */
    public function serializeObject()
    {
        $cat = new Animal(array('name' => 'cat', 'pet' => 1));
        $data = serialize($cat);
        $newCat = unserialize($data);
        $this->assertEquals('cat', $newCat->name);
        $newCat->name = 'lion';
        $this->assertEquals('lion', $newCat->name);
        $this->assertInstanceOf('\Slick\Common\Base', $newCat->setPet(false));
        $this->assertFalse($newCat->isPet());
        unset($cat, $newCat);
    }

    /**
     * Assign values to an object using only magic methods.
     * @test
     * @expectedException \Slick\Common\Exception\UndefinedPropertyException
     * @expectedExceptionMessage Trying to assign a value to an undefined property. Common\Examples\Animal::$_age doesn't exists.
     */
    public function assignValues()
    {
        $cat = new Animal(array('name' => 'cat', 'pet' => 1));
        $obj = $cat->setName('persa cat');
        $this->assertEquals('persa cat', $cat->name);
        $this->assertInstanceOf('\Common\Examples\Animal', $obj);
        $cat->setAge(10);
    }

    /**
     * This should raise an exception whent assigning values to it
     * @test
     * @expectedException \Slick\Common\Exception\ReadOnlyException
     * @expectedExceptionMessage Trying to assign a value to a read only property. Common\Examples\Animal::$_dead has annotation @read.
     */
    public function setReadOnlyProperty()
    {
        $cat = new Animal();
        $cat->setDead(true);
    }

    /**
     * Get property values with magic methods.
     * @test
     * @expectedException \Slick\Common\Exception\WriteOnlyException
     * @expectedExceptionMessage Trying to read the values of a write only property. Common\Examples\Animal::$_sick has annotation @write.
     */
    public function getValues()
    {
        $cat = new Animal(array('name' => 'cat'));
        $this->assertEquals('persa cat', $cat->setName('persa cat')->getName());
        $this->assertNull($cat->getSize());
        $cat->getSick(true);
    }

    /**
     * Get property values with "isProperty" magic method.
     * @test
     * @expectedException \Slick\Common\Exception\WriteOnlyException
     * @expectedExceptionMessage Trying to read the values of a write only property. Common\Examples\Animal::$_sick has annotation @write.
     */
    public function getStateValues()
    {
        $cat = new Animal(array('name' => 'cat', 'pet' => true));
        $this->assertFalse($cat->isTall());
        $this->assertTrue($cat->isPet());
        $cat->isSick();
    }

    /**
     * Call to unimplemented methods should raise an exception
     * @test
     * @expectedException \Slick\Common\Exception\UnimplementedMethodCallException
     * @expectedExceptionMessage The method 'Common\Examples\Animal::die()' its not defined.
     */
    public function callUnimplementedMethod()
    {
        $cat = new Animal();
        $cat->die();
    }

    /**
     * Instantiating a class that does not call parent constructor
     * @test
     * @expectedException \Slick\Common\Exception\BadConstructorException
     * @expectedExceptionMessage The constructor is not correct for use Slick\Common\Base class. You need to call 'parent::__construct()' for the right object initialization.
     */
    public function userABadConstructor()
    {
        $dog = new Examples\BadAnimal();
        $dog->name;
    }

    /**
     * Using Base::equals() to compare objects
     * @test
     */
    public function compareObjects()
    {
        $dog = new Examples\Animal(array('name' => 'Dog'));
        $car = new Examples\Car();
        $this->assertFalse($dog->equals(array()));
        $this->assertFalse($dog->equals($car));
        $bidDog = new Examples\Animal(array('name' => 'Dog'));
        $this->assertTrue($dog->equals($bidDog));
        $cat = new Examples\Animal(array('name' => 'Cat'));
        $this->assertFalse($dog->equals($cat));
        $bidDog->setPet(true);
        $this->assertFalse($dog->equals($bidDog));

    }

    /**
     * Use PHP __isset() magic method tho check property existence
     * @test
     */
    public function validatePropertyExistence()
    {
        $dog = new Examples\Animal(array('name' => 'Dog'));
        $this->assertTrue(isset($dog->name));
        $this->assertFalse(isset($dog->lastName));
    }

}
