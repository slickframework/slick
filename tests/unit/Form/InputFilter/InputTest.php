<?php

/**
 * Input test case
 *
 * @package   Test\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Form\InputFilter;

use Slick\Filter\FilterChain;
use Slick\Filter\Text;
use Slick\Form\InputFilter\Input,
    Slick\Validator\ValidatorChain;
use Slick\Validator\NotEmpty;

/**
 * Input test case
 *
 * @package   Test\Form\InputFilter
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class InputTest extends \Codeception\TestCase\Test
{

    /**
     * Check validator chain
     * @test
     */
    public function checkValidatorChain()
    {
        $input = new Input('test');
        $validatorChain = new ValidatorChain();
        $this->assertInstanceOf(
            'Slick\Validator\ValidatorChain',
            $input->getValidatorChain()
        );
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\InputInterface',
            $input->setValidatorChain($validatorChain)
        );
        $this->assertSame($validatorChain, $input->getValidatorChain());
    }

    /**
     * Check filter chain
     * @test
     */
    public function checkFilterChain()
    {
        $input = new Input('test');
        $filterChain = new FilterChain();
        $this->assertInstanceOf(
            'Slick\Filter\FilterChain',
            $input->getFilterChain()
        );
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\Input',
            $input->setFilterChain($filterChain)
        );
        $this->assertSame($filterChain, $input->getFilterChain());
    }

    /**
     * Verifies the value filtering and value validation
     * @test
     */
    public function verifyValueProcess()
    {
        $input = new Input('test');
        $filter = new Text();
        $input->getFilterChain()->add($filter);
        $text = '<b>Test</b>';
        $this->assertInstanceOf(
            'Slick\Form\InputFilter\Input',
            $input->setValue($text)
        );
        $this->assertEquals($text, $input->getRawValue());
        $this->assertEquals('Test', $input->getValue());

        $validator = new NotEmpty();
        $input->getValidatorChain()->add($validator);
        $this->assertTrue($input->isValid());

        $emptyText = '<b></b>';
        $input->setValue($emptyText);
        $this->assertNull($input->filtered);
        $this->assertEquals('', $input->getValue());
        $this->assertFalse($input->isValid());
        $messages = ['notEmpty' => 'The value cannot be empty.'];
        $this->assertEquals($messages, $input->getMessages());
    }

    /**
     * Check default input values
     * @test
     */
    public function checkDefaultValues()
    {
        $input = new Input('test');
        $this->assertTrue($input->allowEmpty());
        $this->assertFalse($input->isRequired());
    }
}