<?php

/**
 * Numeric validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.1.0
 */

namespace Validator;

use Slick\Validator\Number;

/**
 * Numeric validator test case
 *
 * @package    Test\Validator
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class NumericTest extends \Codeception\TestCase\Test
{

    /**
     * Validate alpha numeric value
     * @test
     */
    public function validateNumericValue()
    {
        $validator = new Number();
        $value = -2;
        $this->assertFalse($validator->isValid('15r'));
        $this->assertTrue($validator->isValid($value));
    }
} 