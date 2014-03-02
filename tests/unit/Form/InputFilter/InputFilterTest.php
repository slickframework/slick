<?php

/**
 * InputFilter test case
 *
 * @package   Test\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form\InputFilter;

use Slick\Filter\Text;
use Slick\Form\InputFilter\Input;
use Slick\Form\InputFilter\InputFilter;
use Slick\Validator\NotEmpty;

/**
 * InputFilter test case
 *
 * @package   Test\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InputFilterTest extends \Codeception\TestCase\Test
{

    /**
     * Create an input filter
     * @test
     */
    public function createInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\InputFilterInterface',
            $inputFilter
        );
    }

    /**
     * Add/check/remove an input in the input filter
     * @test
     * @expectedException \Slick\Form\Exception\InvalidArgumentException
     */
    public function manageInputs()
    {
        $inputFilter = new InputFilter();
        $name = new Input('name');
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\InputFilter',
            $inputFilter->add($name)
        );

        $this->assertTrue($inputFilter->has('name'));

        $this->assertSame($name, $inputFilter->get('name'));

        $this->assertTrue($inputFilter->remove('name'));
        $this->assertFalse($inputFilter->has('name'));

        $inputFilter->add($name, 'userName');
        $this->assertFalse($inputFilter->has('name'));

        $nested = new InputFilter();

        try {
            //adding input filter without name
            $nested->add($inputFilter);
            $this->fail("Add method should throw an exception here");
        } catch (\LogicException $e) {
            $this->assertInstanceOf(
                'Slick\Form\Exception\InvalidArgumentException',
                $e
            );
        }

        $nested->add($inputFilter, 'inputStack');
        $this->assertSame($inputFilter, $nested->get('inputStack'));

        $nested->add(new \StdClass(), 'someName');
    }

    /**
     * Validate data on input filter
     * @test
     */
    public function validateDataOnInputFilter()
    {
        $data = ['name' => ''];
        $name = new Input('name');
        $name->getValidatorChain()->add(new NotEmpty());
        $inputFilter = new InputFilter();
        $inputFilter->add($name);

        $messages = [
            'name' => [
                'notEmpty' => "The value cannot be empty."
            ]
        ];
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\InputFilter',
            $inputFilter->setData($data)
        );
        $this->assertFalse($inputFilter->isValid());
        $this->assertEquals($messages, $inputFilter->getMessages());

        $data['name'] = 'test';
        $inputFilter->setData($data);

        $this->assertTrue($inputFilter->isValid());
    }

    /**
     * Retrieve filtered/raw values
     *@test
     */
    public function retrieveValue()
    {
        $data = ['name' => '<b>test</b>'];
        $name = new Input('name');
        $name->getFilterChain()->add(new Text());
        $inputFilter = new InputFilter();
        $inputFilter->add($name);
        $inputFilter->setData($data);

        $this->assertEquals($data, $inputFilter->getRawValues());
        $this->assertEquals($data['name'], $inputFilter->getRawValue('name'));

        $this->assertEquals(['name' => 'test'], $inputFilter->getValues());
        $this->assertEquals('test', $inputFilter->getValue('name'));
    }
}