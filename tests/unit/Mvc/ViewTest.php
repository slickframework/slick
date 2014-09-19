<?php

/**
 * View test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use Slick\Mvc\View;
use Slick\Template\Template;
use Codeception\TestCase\Test;

/**
 * View test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ViewTest extends Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * Create a view object
     * @test
     * @expectedException \Slick\Mvc\Exception\InvalidArgumentException
     */
    public function createView()
    {
        Template::addPath(__DIR__);
        $view = new View();

        $engine = $view->engine;
        $this->assertInstanceOf('Slick\Template\EngineInterface', $engine);
        $this->assertEmpty($view->data);

        $this->assertInstanceOf('Slick\Mvc\View', $view->setEngineOptions([
            'engine' => 'twig'
        ]));
        $this->assertNotSame($engine, $view->getEngine());

        $this->assertNull($view->get('key', null));
        $this->assertInstanceOf('Slick\Mvc\View', $view->set('key', 'value'));
        $expected = [
            'key' => 'value',
            'one' => '1'
        ];
        $view->set(['one' => '1', 'two' => '2']);
        $this->assertEquals(2, $view->get('two'));
        $this->assertInstanceOf('Slick\Mvc\View', $view->erase('two'));
        $this->assertEquals($expected, $view->data);
        $view->set(true, false);
    }
}
