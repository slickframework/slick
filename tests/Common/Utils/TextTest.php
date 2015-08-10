<?php
 /**
 * TextTest
 *
 * @package Slick\tests\Common\Utils
 * @author    Filipe Silva <filipe.silva@sata.pt>
 * @copyright 2014-2015 Grupo SATA
 * @since     v0.0.0
 */

namespace Slick\tests\Common\Utils;


use Slick\Common\Utils\Text;

class TextTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test match operation on strings.
     *
     * @test
     */
    public function matchString()
    {
        $pattern = "(@[a-zA-Z]+\s*.*)";
        $result = Text::match('* @tag Some\Valu(test=4)', $pattern);
        $this->assertEquals('@tag Some\Valu(test=4)', reset($result));

        $result1 = Text::match('You and me', "You and me");
        $this->assertEquals('You and me', reset($result1));

        $this->assertNull(Text::match('test', 'differen'));
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

}
