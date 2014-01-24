<?php

/**
 * Twig test case
 * 
 * @package   Test\Template\Twig
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Template\Engine;

use Codeception\Util\Stub;
use Slick\Template\Engine\Twig;

/**
 * Twig test case
 * 
 * @package   Test\Template\Twig
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TwigTest extends \Codeception\TestCase\Test
{
    /**
     * Setting and getting template output
     * @test
     * @expectedException Slick\Template\Exception\ParserException
     */
    public function setGetTemplateOutput()
    {
        $paths = array(
            dirname(dirname(dirname(__DIR__))) . '/app/View'
        );
        $engine = new Twig(
            array(
                'paths' => $paths
            )
        );
        $this->assertSame($engine, $engine->initialize());
        $template = "simple.html.twig";
        $engine->parse($template);
        $this->assertEquals($template, $engine->getSource());
        $expected = '<p>test</p>';
        $this->assertEquals($expected, $engine->process(array('var' => 'test')));

        $engine->parse('bas.html.twig');
        $engine->process();
    }

}