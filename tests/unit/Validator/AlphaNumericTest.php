<?php

/**
 * AlphaNumeric validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Validator;
use Slick\Validator\AlphaNumeric;

/**
 * AlphaNumeric validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class AlphaNumericTest extends \Codeception\TestCase\Test
{

    /**
     * Validate alpha numeric value
     * @test
     */
    public function validateAlphaNumericValue()
    {
        $validator = new AlphaNumeric();
        $value = "Test123";
        $this->assertTrue($validator->isValid($value));
        $this->assertFalse($validator->isValid(''));
    }
}