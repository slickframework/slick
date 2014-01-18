<?php

/**
 * Template test case
 * 
 * @package   Test\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Template;

use Codeception\Util\Stub;
use Slick\Template\Template,
    Slick\Template\Engine\Twig;

/**
 * Template test case
 * 
 * @package   Test\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class TemplateTest extends \Codeception\TestCase\Test
{

    /**
     * Initialize a template
     * @test
     * @expectedException Slick\Template\Exception\InvalidArgumentException
     */
    public function initializeATemplate()
    {
        $template = new Template();
        $this->assertInstanceOf('Slick\Template\Template', $template);
        $template = $template->initialize();
        $this->assertInstanceOf('Slick\Template\EngineInterface', $template);
        $this->assertInstanceOf('Slick\Template\Engine\Twig', $template);

        $template = new Template(array('engine' => 'Foo'));
        $template->initialize();
    }

    /**
     * Use custom defined class
     * @test
     * @expectedException Slick\Template\Exception\InvalidArgumentException
     */
    public function useCustomClass()
    {
        $class = 'Template\MyEngine';
        $template = new Template(
            array(
                'engine' => $class
            )
        );
        $template = $template->initialize();
        $this->assertInstanceOf($class, $template);

        $template = new Template(
            array(
                'engine' => '\StdClass'
            )
        );
        $template->initialize();
    }


}

class MyEngine extends twig
{

}