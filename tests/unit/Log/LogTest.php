<?php
namespace Log;

use Codeception\Util\Stub;
use Slick\Log\Log;

class LogTest extends \Codeception\TestCase\Test
{
   
    /**
     * Create a logger from static funcion 
     * @test
     */
    public function createALogger()
    {
        $logger = Log::logger();
        $this->assertInstanceOf('\Monolog\Logger', $logger);
        $logger->addInfo('My logger is now ready');
    }

}