<?php

/**
 * Factory test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form;
use Slick\Form\Element;
use Slick\Form\Factory;

/**
 * Factory test case
 *
 * @package   Test\FileSystem
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FactoryTest extends \Codeception\TestCase\Test
{

    /**
     * Create form using form factory
     * @test
     */
    public function createFromFromFactory()
    {
        $form = Factory::create(
            'testForm',
            [
                'fieldset' => [
                    'type' => 'fieldset',
                    'elements' => [
                        'username' => [
                            'type' => 'text',
                            'label' => 'Enter your username',
                            'attributes' => [
                                'class' => 'test-class',
                                'placeholder' => 'Some useful name'
                            ],
                            'input' => [
                                'required' => true,
                                'validation' => [
                                    'notEmpty' => 'Username cannot be empty'
                                ],
                                'filters' => [
                                    'text'
                                ]
                            ]
                        ]
                    ]
                ],
                'email' => [
                    'type' => 'text',
                    'label' => 'E-mail address',
                    'input' => [
                        'validation' => [
                            'email' => 'You must enter a valid email address.',
                            'notEmpty' => 'Email cannot be empty'
                        ]
                    ]
                ]
            ]
        );
        $this->assertInstanceOf(
            'Slick\Form\Form',
            $form
        );
        $this->assertInstanceOf('Slick\Form\Element\Text', $form->get('email'));
        $this->assertInstanceOf('Slick\Form\Fieldset', $form->get('fieldset'));

        $form->setData(['email' => '', 'username' => '<b>Foo</b>']);
        $this->assertFalse($form->isValid());
        $messages = [
            'email' => [
                'email' => 'You must enter a valid email address.',
                'notEmpty' => 'Email cannot be empty'
            ]
        ];
        $this->assertEquals($messages, $form->getMessages());
    }

    /**
     * Create a custom element form
     * @test
     */
    public function createCustomElementForm()
    {
        $form = Factory::create(
            'testForm',
            [
                'name' => [
                    'type' => 'Form\MyElement',
                    'input' => [
                        'validation' => [
                            'notEmpty' => 'Name cannot be empty.'
                        ]
                    ]
                ]
            ]
        );
        $this->assertInstanceOf('Form\MyElement', $form->get('name'));
    }

    /**
     * Trying to create a form with invalid class
     * @test
     * @expectedException \Slick\Form\Exception\UnknownElementException
     */
    public function createdFormWithInvalidClass()
    {
        Factory::create(
            'testForm',
            [
                'OtherTest' => [
                    'type' => 'unknown'
                ]
            ]
        );
    }
}

/**
 * Class for tests
 */
class MyElement extends Element
{

}