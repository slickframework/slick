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

    protected $terms = array(
        'person' => 'people',
        'man' => 'men',
        'user' => 'users',
        'knife' => 'knives',
        'life' => 'lives',
        'ox' => 'oxen',
        'child' => 'children',
        'woman' => 'women',
        'crisis' => 'crises',
    );

    /**
     * Singular conversion tests
     * @test
     */
    public function singular()
    {
        foreach ($this->terms as $singular => $plural) {
            $this->assertEquals($singular, Text::singular($plural));
        }
    }

    /**
     * Singular conversion tests
     * @test
     */
    public function plural()
    {
        foreach ($this->terms as $singular => $plural) {
            $this->assertEquals($plural, Text::plural($singular));
        }
    }

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
     * Test split operation on strings
     * 
     * @test
     */
    public function splitAString()
    {
        $text = '@tag Some\\Value(param1=val1, param2=val2)';
        $expected = array('@tag', 'Some\\Value(param1=val1, param2=val2)');
        $this->assertEquals($expected, Text::split($text, '[\s*]', 2));
    }

    /**
     * Check camel case split
     *
     * @test
     */
    public function camelCaseSplit()
    {
        $string = "thisIsACamelCaseString";
        $expected = "this Is A Camel Case String";
        $this->assertEquals($expected, Text::camelCaseToSeparator($string));
        $obj = new \stdClass();
        $this->assertEquals($obj, Text::camelCaseToSeparator($obj));
        if (Text::$hasPcreUnicodeSupport) {
            Text::$hasPcreUnicodeSupport = false;
            // Not so important as PCRE is installed on newer systems
            $this->assertEquals(
                'this Is ACamel Case String',
                Text::camelCaseToSeparator($string)
            );
            Text::$hasPcreUnicodeSupport = true;
        }

    }

}
