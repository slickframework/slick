<?php
namespace Http;
use Codeception\Util\Stub;
use Slick\Http\Response;

class ResponseTest extends \Codeception\TestCase\Test
{

    /**
     * Trying to create a response from string
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage A valid response status line was not found in the provided string
     */
    public function createFromString()
    {
        $rsp = "HTTP/1.0 200 OK" . "\r\n";
        $rsp .= "Accept-Ranges: bytes" . "\r\n";
        $rsp .= "Connection: Keep-Alive" . "\r\n";
        $rsp .= "Content-Language: en" . "\r\n";
        $rsp .= "Content-Length: 44" . "\r\n";
        $rsp .= "Content-Location: index.html.en" . "\r\n";
        $rsp .= "Content-Type: text/html" . "\r\n";
        $rsp .= "Date: Mon, 28 Oct 2013 23:03:01 GMT" . "\r\n";
        $rsp .= "Keep-Alive: timeout=5, max=100" . "\r\n";
        $rsp .= "Last-Modified: Sat, 26 Oct 2013 23:50:01 GMT" . "\r\n";
        $rsp .= "Server: Apache/2.2.24 (Unix) DAV/2 PHP/5.4.17 mod_ssl/2.2.24 OpenSSL/0.9.8y" . "\r\n";
        $rsp .= "TCN: choice" . "\r\n";
        $rsp .= "Vary: negotiate" . "\r\n";
        $rsp .= "\r\n";
        $rsp .= "<html><body><h1>It works!</h1></body></html>";
        $response = Response::fromString($rsp);
        $this->assertEquals('OK', $response->getReasonPhrase());
        $response->setReasonPhrase(null);
        $this->assertEquals('OK', $response->getReasonPhrase());
        $this->assertEquals(Response::VERSION_10, $response->getVersion());
        $this->assertInstanceOf('Slick\Http\Response', $response);
        $this->assertTrue($response->isOk());
        $this->assertTrue($response->isSuccess());
        $this->assertFalse($response->isInformational());
        $this->assertFalse($response->isForbidden());
        $this->assertFalse($response->isServerError());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isClientError());
        $this->assertFalse($response->isNotFound());
        $this->assertEquals($rsp, $response->toString());
        unset($response);

        $badResponse = Response::fromString("Dummy HTTP message");
    }

    /**
     * Creata a basic response with status line.
     * @test
     */
    public function createSimpleResponse()
    {
        $response = Response::fromString("HTTP/1.1 404 Not Found");
        $this->assertInstanceOf('Slick\Http\Response', $response);
        unset($response);
    }

    /**
     * Try to change a status code
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function changeStatusCode()
    {
        $response = new Response();
        $this->assertInstanceOf('Slick\Http\Response', $response->setStatusCode(404));
        $this->assertTrue($response->isNotFound());
        $response->setStatusCode("200 Ok");
    }

    /**
     * Try to set response headers
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage nvalid headers provided. It must be a string or an array with header values.
     */
    public function setBadHeaders()
    {
        $response = new Response();
        $response->setHeaders(null);
    }

}