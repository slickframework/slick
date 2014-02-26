<?php

/**
 * Element test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form;

use Slick\Form\Element;
use Slick\Form\InputFilter\Input;

/**
 * Element test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ElementTest extends \Codeception\TestCase\Test
{

    /**
     * Test element creation
     * @test
     */
    public function createElement()
    {
        $element = new Element(['name' => 'username']);
        $this->assertInstanceOf('Slick\Form\ElementInterface', $element);
        $this->assertEquals('username', $element->getName());
        $this->assertInstanceOf(
            'Slick\Form\Element',
            $element->setLabel("user name")
        );
        $this->assertEquals('user name', $element->getLabel());
        $this->assertInstanceOf(
            'Slick\Form\Element',
            $element->setName("username")
        );
        $this->assertInstanceOf(
            'Slick\Form\Element',
            $element->setValue("test")
        );
        $this->assertEquals('test', $element->getValue());
        $this->assertFalse($element->hasAttribute('id'));
        $this->assertInstanceOf(
            'Slick\Form\Element',
            $element->setAttribute('id', 'username')
        );
        $this->assertEquals('username', $element->getAttribute('id'));
        $attributes = ['type' => 'text', 'id' => 'username'];
        $this->assertEquals($attributes, $element->getAttributes());
        $attributes['placeholder'] = 'username';
        $this->assertInstanceOf('Slick\FOrm\Element', $element->setAttributes($attributes));
        $this->assertTrue($element->hasAttribute('placeholder'));

        $this->assertInstanceOf('Slick\Form\Element', $element->setMessage('test', 'Hello'));
        $this->assertEquals(['test' => 'Hello'], $element->getMessages());
    }

    /**
     * Checks the associated input in an element
     * @test
     */
    public function checkInputInAnElement()
    {
        $username = new Element(['name' => 'username']);
        $testInput = new Input(['name' => 'test']);
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\Input',
            $username->getInput()
        );
        $this->assertEquals(
            $username->getName(),
            $username->getInput()->name
        );
        $this->assertInstanceOf(
            'Slick\Form\Element',
            $username->setInput($testInput)
        );
        $this->assertSame($testInput, $username->getInput());
    }
}