<?php
namespace Http\PhpEnvironment;

use Slick\Http\PhpEnvironment\Response;

class ResponseTest extends \Codeception\TestCase\Test
{

    /**
     * The SUT object
     * @var \Slick\Http\PhpEnvironment/Response
     */
    protected $_response;

    /**
     * Sets the SUT object to test
     */
    protected function _before()
    {
        parent::_before();
        $this->_response = new Response();
    }

    /**
     * Cleanup for next test
     */
    protected function _after()
    {
        unset($this->_response);
        parent::_after();
    }

    /**
     * Sendig output headers
     * 
     * @test
     */
    public function sendHeaders()
    {
        $headers = array(
            'Pragma' => 'no-cache'
        );
        $this->_response->setHeader('Pragma', 'no-cache');
        $this->assertEquals($headers, $this->_response->getHeaders());
        $response = @$this->_response->sendHeaders();
        $this->assertSame($response, $this->_response);
        $this->assertTrue($this->_response->headersSent);
        $this->_response->sendHeaders();
        $this->assertTrue($this->_response->headersSent);
    }

    /**
     * Sendig output content
     * 
     * @test
     */
    public function sendContent()
    {
        $this->_response->setContent("Hello world");
        ob_start();
        $response = $this->_response->sendContent();
        $content = ob_get_clean();
        $this->assertSame($response, $this->_response);
        $newCall = $this->_response->sendContent();
        $this->assertSame($newCall, $this->_response);
        $this->assertEquals("Hello world", trim($content));

        $sendCall = $this->_response->send();
        $this->assertSame($sendCall, $this->_response);
    }

    /**
     * Trying to detec the server HTTP version to use.
     * @test
     */
    public function detectServerHttpVersion()
    {
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $response = new Response();
        $this->assertEquals(Response::VERSION_11, $response->getVersion());
    }

}