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

}
