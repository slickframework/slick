<?php

/**
 * Application test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use Slick\Mvc\Router;
use Slick\Di\Container;
use Slick\Di\Definition;
use Slick\Mvc\Application;
use Slick\Di\ContainerBuilder;
use Slick\Mvc\Events\Bootstrap;
use Slick\Configuration\Configuration;
use Zend\EventManager\SharedEventManager;

/**
 * Application test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ApplicationTest extends \Codeception\TestCase\Test
{
   /**
    * @var \CodeGuy
    */
    protected $codeGuy;

    /**
     * @var int
     */
    private $_beforeBootstrap = 0;

    private $_afterBootstrap = 0;

    /**
     * @var Container
     */
    private $_container;

    protected function _before()
    {
        parent::_before();
        Configuration::addPath(dirname(dirname(__DIR__)) .'/app/Configuration');
    }

    /**
     * Create an MVC application
     * @test
     */
    public function startApplication()
    {
        $application = new Application();
        $request = $application->request;
        $this->assertInstanceOf('Zend\Http\PhpEnvironment\Request', $request);
        $response = $application->response;
        $this->assertInstanceOf('Zend\Http\PhpEnvironment\Response', $response);
        $this->assertInstanceOf(
            'Slick\Configuration\Driver\DriverInterface',
            $application->getConfiguration()
        );
    }

    /**
     * Trigger the application Bootstrap events
     *
     * @test
     */
    public function triggerBootstrapEvents()
    {
        /** @var SharedEventManager $shared */
        $shared = $this->getContainer()->get('sharedEventManager');
        $shared->attach(
            'Slick\Mvc\Application',
            Bootstrap::BEFORE_BOOTSTRAP,
            function(Bootstrap $event){
                $this->_beforeBootstrap = 1;
                $this->assertInstanceOf('Slick\Mvc\Events\Bootstrap', $event);
                $this->assertInstanceOf('Slick\Mvc\Router', $event->getRouter());
                $this->assertInstanceOf('Slick\Mvc\Application', $event->getApplication());
            }
        );
        $shared->attach(
            'Slick\Mvc\Application',
            Bootstrap::AFTER_BOOTSTRAP,
            function (Bootstrap $event) {
                $this->_afterBootstrap = 1;
                $this->assertInstanceOf('Slick\Mvc\Router', $event->getRouter());
                $this->assertInstanceOf('Slick\Mvc\Application', $event->getApplication());
            }
        );
        $this->getContainer()->set('sharedEventManager', $shared);

        $app = new Application();
        $app->bootstrap();
        $this->assertEquals(1, $this->_beforeBootstrap);
        $this->assertEquals(1, $this->_afterBootstrap);
    }

    /**
     * Returns the internal dependency injector container
     *
     * @return Container The dependency injector
     */
    private function getContainer()
    {
        if (is_null($this->_container)) {
            $def = [
                'configuration' => Definition::factory(
                    ['Slick\Configuration\Configuration', 'get'],
                    ['config', 'php']
                ),
                'sharedEventManager' => Definition::object(
                    'Zend\EventManager\SharedEventManager'
                )
            ];
            $this->_container = ContainerBuilder::buildContainer($def);
        }
        return $this->_container;
    }
}
