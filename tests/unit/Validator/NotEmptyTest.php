<?php

/**
 * NotEmpty validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Validator;
use Slick\Validator\AbstractValidator;
use Slick\Validator\NotEmpty;
use Slick\Validator\StaticValidator;
use Slick\Validator\ValidatorChain;
use Slick\Validator\ValidatorInterface;

/**
 * NotEmpty validator test case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class NotEmptyTest extends \Codeception\TestCase\Test
{

    /**
     * Not empty validation
     * @test
     */
    public function validateNotEmptyValue()
    {
        $value = '';
        $this->assertFalse(StaticValidator::isValid('notEmpty', $value));
        $expected = ['notEmpty' => 'The value cannot be empty.'];
        $this->assertEquals($expected, StaticValidator::geMessages());

        $validator = new NotEmpty();

        $message = "Username cannot be empty. Add some text.";
        $result = $validator->setMessage('notEmpty', $message);
        $this->assertInstanceOf('Slick\Validator\NotEmpty', $result);

        $this->assertFalse($validator->isValid(''));
        $expected = ['notEmpty' => "Username cannot be empty. Add some text."];
        $this->assertEquals($expected, $validator->getMessages());
    }

    /**
     * Create custom validator class
     * @test
     * @expectedException \Slick\Validator\Exception\UnknownValidatorClassException
     */
    public function customValidator()
    {
        $validator = new MyValidator();
        $this->assertFalse($validator->isValid(false));
        $this->assertEquals(
            ['dummy' => 'The value foo is not valid.'],
            $validator->getMessages()
        );

        $this->assertTrue(StaticValidator::isValid('Validator\MyValidator', true));
        StaticValidator::isValid('foo', 'bar');
    }

    /**
     * Check validation chain
     * @test
     */
    public function validatorChain()
    {
        $chain = new ValidatorChain();
        $chain->add(new NotEmpty());
        $value = null;
        $this->assertFalse($chain->isValid($value));
        $this->assertEquals(
            ['notEmpty' => 'The value cannot be empty.'],
            $chain->getMessages()
        );
    }

}

class MyValidator extends AbstractValidator
    implements ValidatorInterface
{

    /**
     * @readwrite
     * @var array Message templates
     */
    protected $_messageTemplates = [
        'dummy' => 'The value %s is not valid.'
    ];

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!$value) {
            $this->addMessage('dummy', 'foo');
        }
        return $value;
    }
}