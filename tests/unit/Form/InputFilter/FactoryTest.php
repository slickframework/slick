<?php

/**
 * InputFilter factory test case
 *
 * @package   Test\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form\InputFilter;
use Slick\Form\InputFilter\Factory;


/**
 * InputFilter factory test case
 *
 * @package   Test\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FactoryTest extends \Codeception\TestCase\Test
{
    /**
     * create an input filter using factory
     * @test
     */
    public function createInputFilter()
    {
        $inputFilter = Factory::create(
            [
                'name' => [
                    'name' => 'name',
                    'required' => true,
                    'filters' => ['text'],
                    'validation' => [
                        'notEmpty' => "Name cannot be empty."
                    ]
                ],
                [
                    'name' => 'email',
                    'required' => true,
                    'validation' => [
                        'email' => "Please provide a valid email address.",
                        'notEmpty' => "The email address cannot be empty."
                    ]
                ]
            ]
        );
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\InputFilter',
            $inputFilter
        );
        $this->assertTrue($inputFilter->has('name'));
        $this->assertTrue($inputFilter->has('email'));

        $this->assertTrue($inputFilter->get('name')->isRequired());


        $this->assertInstanceOf(
            'Slick\Filter\Text',
            $inputFilter->get('name')->getFilterChain()->filters[0]
        );

        $this->assertInstanceOf(
            'Slick\Validator\Email',
            $inputFilter->get('email')->getValidatorChain()->validators[0]
        );
    }

    /**
     * Use a factory created input filter
     * @test
     */
    public function useFactoryCreatedInputFilter()
    {
        $factory = new Factory();
        $inputFilter = $factory->newInputFilter(
            [
                'name' => [
                    'name' => 'name',
                    'required' => true,
                    'filters' => ['text'],
                    'validation' => [
                        'notEmpty' => "Name cannot be empty."
                    ]
                ],
                [
                    'name' => 'email',
                    'required' => true,
                    'validation' => [
                        'email' => "Please provide a valid email address.",
                        'notEmpty' => "The email address cannot be empty."
                    ]
                ]
            ]
        );

        $inputFilter->setData(
            [
                'name' => '<b></b>',
                'email' => ''
            ]
        );

        $expected = [
            'name' => [
                'notEmpty' => 'Name cannot be empty.'
            ],
            'email' => [
                'email' => 'Please provide a valid email address.',
                'notEmpty' => 'The email address cannot be empty.'
            ]
        ];

        $this->assertFalse($inputFilter->isValid());
        $this->assertEquals($expected, $inputFilter->getMessages());

        $inputFilter->setData(
            [
                'name' => '<b>Test</b>',
                'email' => 'some.name@example.com'
            ]
        );

        $this->assertTrue($inputFilter->isValid());
        $this->assertEquals('Test', $inputFilter->getValue('name'));
    }
}