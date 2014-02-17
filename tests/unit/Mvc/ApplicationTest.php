<?php

/**
 * Application test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Mvc;

use Codeception\Util\Stub;
use Slick\Configuration\Configuration;
use Slick\Mvc\Application;
use Slick\Mvc\Controller;
use Slick\Mvc\MvcEvent;
use Slick\Template\Template;

/**
 * Application test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ApplicationTest extends \Codeception\TestCase\Test
{

    protected $_bootstrap = false;

    /**
     * Create an application
     * @test
     */
    public function createApplication()
    {
        Template::addPath(__DIR__);
        Configuration::addPath(dirname(dirname(__DIR__)).'/app/Configuration');
        $config = Configuration::get('config');

        $app = new Application();
        $config = $app->getConfiguration('config');
        $config->set('router', ['namespace' => 'Mvc']);
        $app->configuration = $config;
        $_GET['url'] = 'appTests/run';
        $router = $app->getRouter();
        $router->setConfiguration($config);
        $app->router = $router;

        $app->getEventManager()->attach(
            MvcEvent::EVENT_BOOTSTRAP,
            function($event) {
                $this->_bootstrap = true;
                $this->assertInstanceOf('Slick\Mvc\MvcEvent', $event);

                $this->assertInstanceOf(
                    'Zend\Http\PhpEnvironment\Response',
                    $event->getResponse()
                );

                $this->assertInstanceOf(
                    'Zend\Http\PhpEnvironment\Request',
                    $event->getRequest()
                );

                $this->assertInstanceOf(
                    'Slick\Mvc\Router',
                    $event->getRouter()
                );
            }
        );

        $app->bootstrap();
        $this->assertTrue($GLOBALS['bootstrapTest']);
        $this->assertTrue($GLOBALS['routeTest']);

        $app->run();

        $this->assertEquals('<p>Hello test</p>', $app->getResponse()->getContent());
        unset($_GET['url']);
    }
}

class AppTests extends Controller
{

    public function run()
    {
        $this->renderLayout = false;
        $this->set('name', 'test');
    }
}