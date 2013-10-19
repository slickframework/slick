<?php

/**
 * Text test case
 * 
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Utility;

use Slick\Utility\Text;

/**
 * Text class test case
 * 
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class TextTest extends \Codeception\TestCase\Test
{

    /**
     * @var \CodeGuy
     */
    protected $codeGuy;

    /**
     * Test match operation on strings.
     * 
     * @test
     */
    public function matchString()
    {
        $pattern = "(@[a-zA-Z]+\s*.*)";
        $result = Text::match('* @tag Some\Valu(test=4)', $pattern);
        $this->assertTrue(is_array($result));
        $this->assertEquals('@tag Some\Valu(test=4)', reset($result));
        $result1 = Text::match('You and me', "You and me");
        $this->assertEquals('You and me', reset($result1));
        $this->assertNull(Text::match('test', 'differen'));
    }

    /**
     * Teste split opereatin on strings
     * 
     * @test
     */
    public function splitAString()
    {
        $text = '@tag Some\\Value(param1=val1, param2=val2)';
        $expected = array('@tag', 'Some\\Value(param1=val1, param2=val2)');
        $this->assertEquals($expected, Text::split($text, '[\s*]', 2));
    }

}
