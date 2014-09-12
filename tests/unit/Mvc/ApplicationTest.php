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

use Codeception\Util\Stub;
use Slick\Mvc\Application;

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

}
