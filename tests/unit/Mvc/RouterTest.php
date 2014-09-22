<?php

/**
 * Router test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use Codeception\Util\Stub;
use Slick\Mvc\Application;
use Slick\Configuration\Configuration;
use Slick\Mvc\Exception;

/**
 * Router test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RouterTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * Create router
     * @test
     * @expectedException \Slick\Mvc\Exception\RouterException
     */
    public function createRouter()
    {
        Configuration::addPath(dirname(dirname(__DIR__)).'/app/Configuration');
        $_GET['url'] = '/';
        $app = new Application();
        $router = $app->getRouter();
        $router->map('/[:controller]?/?[:action]?', [], 'default');
        $router->map(
            '/article/[*:slug]/[edit|delete:action]?',
            ['controller' => 'articles', 'namespace' => '']
        );
        $router->map(
            '/article/[*:slug]',
            ['action' => 'show', 'controller' => 'articles']
        );
        $info = $router->filter();
        $this->assertEquals('pages', $info->getControllerName());
        $this->assertEquals('Controllers\\Pages', $info->controller);

        $_GET['url'] = '/article/some-title-here/edit';
        $app = new Application();
        $router->application = $app;
        $info = $router->filter();
        $this->assertEquals('Articles', $info->controller);
        $this->assertEquals('edit', $info->action);
        $this->assertEquals('some-title-here', $info->getArgument('slug'));

        try {
            $router->map('/', [], 'default');
            $this->fail("Repeated name should throw an exception");
        } catch (Exception $exp) {
            $this->assertTrue($exp instanceof Exception\InvalidArgumentException);
        }

        $_GET['url'] = '';
        $app = new Application();
        $router = $app->getRouter();
        $router->filter();
    }

}