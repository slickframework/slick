<?php

/**
 * This file is part of slick/common package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Common\Annotation\TokenParser;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Common\Annotation\TokenParser\Token;

/**
 * Token Test case
 *
 * @package Slick\Tests\Common\Annotation\TokenParser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenTest extends TestCase
{

    /**
     * Data used in token creation tests
     * @return array
     */
    public function tokenData()
    {
        return [
            'openTag' => [[T_OPEN_TAG, '<?php'], 'T_OPEN_TAG', '<?php'],
            'echo' => [[T_ECHO, 'echo'], 'T_ECHO', 'echo'],
            'semicolon' => [';', 'UNKNOWN', ';'],
        ];
    }

    /**
     * @dataProvider tokenData
     * @param array $data
     * @param mixed $expected
     */
    public function testGetTokenName($data, $expected)
    {
        $token = new Token($data);
        $this->assertEquals($expected, $token->getName());
    }

    /**
     * @dataProvider tokenData
     *
     * @param string[]|string $data
     * @param string $name
     * @param string $value
     */
    public function testTokenValue($data, $name, $value)
    {
        $token = new Token($data);
        $this->assertEquals($value, $token->getValue());
    }

    public function testLineNumberWhenAvailable()
    {
        $token = new Token([T_ABSTRACT, 'abstract', 6]);
        $this->assertEquals(6, $token->getLineNumber());
    }
}
