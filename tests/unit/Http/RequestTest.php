<?php

/**
 * Http Request test case
 *
 * @package   Test\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Http;

use Codeception\Util\Stub,
    Slick\Http\Request;

/**
 * RequestTest
 *
 * @package   Test\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RequestTest extends \Codeception\TestCase\Test
{
   
   protected $_requestStr = <<<EOR
GET /page.html HTTP/1.1
\r\n
HeaderField1: header-value1
\r\n
HeaderField2: header-value2
\r\n\r\n

Here is some content
EOR;

    /**
     * Creates a request using a string
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage A valid request line was not found in the provided string
     */
    public function createRequestFromString()
    {
        $request = Request::fromString($this->_requestStr);
        $this->assertInstanceOf('\Slick\Http\Request', $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals(Request::VERSION_11, $request->getVersion());
        $this->assertEquals('/page.html', $request->getUri());
        $this->assertEquals('header-value1', $request->getHeader('HeaderField1', false));
        $this->assertTrue($request->hasHeader('HeaderField2'));
        $this->assertInstanceOf('\Slick\Http\Request', $request->setHeader('test12', '1234'));
        $this->assertTrue($request->hasHeader('HeaderField2'));
        $this->assertTrue($request->hasHeader('test12'));
        $this->assertFalse($request->getHeader('test', false));
        $this->assertTrue($request->isGet());
        $this->assertFalse($request->isHead());
        $this->assertFalse($request->isOptions());
        $this->assertFalse($request->isPropFind());
        $this->assertFalse($request->isTrace());
        $this->assertFalse($request->isConnect());
        $this->assertFalse($request->isPatch());
        $this->assertFalse($request->isPost());
        $this->assertFalse($request->isPut());
        $this->assertFalse($request->isDelete());

        $simpleRequest = Request::fromString('OPTIONS * HTTP/1.1');
        $this->assertInstanceOf('\Slick\Http\Request', $simpleRequest);
        $this->assertTrue($simpleRequest->isOptions());

        Request::fromString("Test");
    }

    /**
     * Testing the seturi method
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid URI passed as string (ssl::My buggy URI!)
     */
    public function createUriFromString()
    {
        $request = new Request();
        $request->setUri('ssl::My buggy URI!');
    }

    /**
     * Testing the seturi method with wron object type
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage URI must be an instance of Zend\Uri\Http or a string
     */
    public function createUriFromObject()
    {
        $request = new Request();
        $request->setUri(new \StdClass());
    }

    /**
     * Set a request http version
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage Not valid or not supported HTTP version: 11
     */
    public function setRequestVersion()
    {
        $request = new Request(array('version' => Request::VERSION_10));
        $this->assertEquals('1.0', $request->getVersion());
        $request->setVersion("11");
    }

    /**
     * Check if a request is a xml request
     * @test
     */
    public function checkXmlRequest()
    {
        $request = Request::fromString(
            "GET page.html HTTP/1.1\r\nX-Requested-With: XMLHttpRequest\r\n"
        );
        $this->assertTrue($request->isXmlHttpRequest());
        $noXml = Request::fromString("GET page.html HTTP/1.1\r\n");
        $this->assertFalse($noXml->isXmlHttpRequest());
        unset($request, $noXml);
    }

    /**
     * Check if a request is a flash request
     * @test
     */
    public function checkFlashRequest()
    {
        $request = Request::fromString(
            "GET page.html HTTP/1.1\r\nUser-Agent: Here comes flash\r\n"
        );
        $this->assertTrue($request->isFlashRequest());
        $noFlash = Request::fromString("GET page.html HTTP/1.1\r\n");
        $this->assertFalse($noFlash->isFlashRequest());
        unset($request, $noFlash);
    }

    /**
     * Check the string ouput of a request 
     * @test
     */
    public function createRequestAsString()
    {
        $request = new Request(
            array(
                'version' => Request::VERSION_10,
                'uri' => 'otherPage.php',
                'headers' => array(
                    'User-Agent' => 'PHP Test'
                ),
                'content' => 'Hello'
            )
        );
        $expected  = "GET otherPage.php HTTP/1.0\r\n";
        $expected .= "User-Agent: PHP Test\r\n";
        $expected .= "\r\n";
        $expected .= "Hello";
        $this->assertEquals($expected, $request->toString());
    }

}