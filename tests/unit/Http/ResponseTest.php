<?php
namespace Http;
use Codeception\Util\Stub;
use Slick\Http\Response;

class ResponseTest extends \Codeception\TestCase\Test
{   

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * Trying to create a response from string
     * @test
     */
    public function createFromString()
    {
        $rsp = "HTTP/1.0 200 OK" . "\r\n";
        $response = Response::fromString($rsp);
        $this->assertInstanceOf('Slick\Http\Response', $response);
    }

}