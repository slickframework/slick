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
use Codeception\Util\Stub;
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
     */
    public function createView()
    {
        Template::addPath(__DIR__);
        $view = new View();

        $this->assertInstanceOf('Slick\Template\EngineInterface', $view->engine);
        $this->assertEmpty($view->data);

        $this->assertNull($view->get('key', null));
        $this->assertInstanceOf('Slick\Mvc\View', $view->set('key', 'value'));
        $this->assertEquals(['key' => 'value'], $view->data);
    }
}
