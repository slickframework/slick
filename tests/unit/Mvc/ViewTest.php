<?php

/**
 * View test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Mvc;
use Codeception\Util\Stub;
use Slick\Mvc\View;
use Slick\Template\Template;

/**
 * View test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ViewTest extends \Codeception\TestCase\Test
{

    /**
     * Create a normal view
     * @test
     * @expectedException \Slick\Mvc\View\Exception\InvalidDataKeyException
     */
    public function createView()
    {
        Template::addPath(__DIR__);
        $view = new View(['file' => 'test.html.twig']);
        $view->set('foo', 'foo');
        $bar = 'bar'; $baz = 'baz';
        $view->set(compact('bar', 'baz'));
        $this->assertEquals('foo', $view->data['foo']);
        $view->erase('bar');
        $this->assertFalse(isset($view->data['bar']));
        $this->assertEquals('baz', $view->get('baz'));
        $this->assertNull($view->get('other', null));
        $this->assertEquals('<text>foo</text>', $view->render());
        $view->set(1, 1);
    }
}