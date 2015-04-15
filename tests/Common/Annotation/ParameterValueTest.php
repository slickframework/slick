<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\tests\Common\Annotation;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Annotation\ParameterValue;

/**
 * Analyse parameters values test case
 *
 * @package Slick\Tests\Common
 */
class ParameterValueTest extends TestCase
{

    /**
     * @dataProvider
     * @return array
     */
    public function valuesProvider()
    {
        $json = "{\"a\":1,\"b\":2,\"c\":3,\"d\":4,\"e\":5}";
        return [
            'array' => ["[one, two, another]", ['one', 'two', 'another']],
            'string' => ['justAString', 'justAString'],
            'json' => [$json, json_decode($json)],
            'boolean true' => ['true', true],
            'boolean false' => ['false', false],
            'null' => ['null', NULL],
            'integer' => ['1', 1],
            'double' => ['1.01', 1.01],
        ];
    }

    /**
     * @dataProvider valuesProvider
     * @param string $val
     * @param mixed $expected
     */
    public function testCheckRealValue($val, $expected)
    {
        $value = new ParameterValue($val);
        if (!is_null($expected)) {
            $this->assertInternalType(
                gettype($expected),
                $value->getRealValue()
            );
            $this->assertEquals($expected, $value->getRealValue());
        } else {
            $this->assertNull($value->getRealValue());
        }
    }
}
