<?php
namespace Http\PhpEnvironment;

use Slick\Http\PhpEnvironment\Request;

class RequestTest extends \Codeception\TestCase\Test
{

    /**
     * @var array $_POST values for tests.
     */
    protected $_post = array(
        'Animal' => array(
            'id' => '2',
            'name' => 'cat',
            'pet' => '1'
        )
    );

    /**
     * @var array $_POST values for tests.
     */
    protected $_get = array(
        'Car' => array(
            'id' => '1',
            'brand' => 'Ferrai',
            'model' => '2002 575M Maranello'
        )
    );

    /**
     * @var array $_FILES values for tests.
     */
    protected $_files = array(
        "upload" =>array(
            "name" => array(
                0 => "file0.txt",
                1 => "file1.txt",
                'test' => array(
                    2 => "file2.txt"
                )
            ),
            "type" => array(
                0 => "text/plain",
                1 => "text/plain",
                'test' => array(
                    2 => "text/plain"
                )
            ),
            "tmp_name" => array(
                0 => "/tmp/blablabla",
                1 => "/tmp/phpyzZxta",
                'test' => array(
                    2 => "/tmp/phpn3nopO"
                )
            ),
            "error" =>array(
                0 => 0,
                1 => 0,
                'test' => array(
                    2 => 0
                )
            ),
            "size" =>array(
                0 => 0,
                1 => 0,
                'test' => array(
                    2 => 0
                )
            )
        )
    );

    /**
     * @var array Expected structure of files.
     */
    protected $_expectedFiles = array(
        'upload' => array(
            array(
                'name' => 'file0.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/blablabla',
                'error' => 0,
                'size' => 0
            ),
            array(
                'name' => 'file1.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/phpyzZxta',
                'error' => 0,
                'size' => 0
            ),
            'test' => array(
                    2 => array(
                    'name' => 'file2.txt',
                    'type' => 'text/plain',
                    'tmp_name' => '/tmp/phpn3nopO',
                    'error' => 0,
                    'size' => 0
                ),
            )

        )
    );

    /**
     * @var array $_POST backup
     */
    protected $_tmpPost;

    /**
     * @var array $_GET backup
     */
    protected $_tmpGet;

    /**
     * @var array $_Files backup
     */
    protected $_tmpFiles;

    /**
     * @var array $_Server backup
     */
    protected $_tmpServer;

    /**
     * Sets global variables for tests.
     */
    protected function _before()
    {
        parent::_before();
        $this->_tmpPost = $_POST;
        $_POST = $this->_post;

        $this->_tmpPost = $_GET;
        $_GET = $this->_get;

        $this->_tmpFiles = $_FILES;
        $_FILES = $this->_files;

        $this->_tmpServer = $_SERVER;
    }

    /**
     * Clears all for next test.
     */
    protected function _after()
    {
        $_POST = $this->_tmpPost;
        $_GET = $this->_tmpGet;
        $_FILES = $this->_tmpFiles;
        $_SERVER = $this->_tmpServer;
        parent::_after();
    }

    /**
     * Verifies the corrent construction for $_POST values.
     * 
     * @test
     */
    public function checkPostParams()
    {
        $request = new Request();
        $this->assertEquals($this->_post, $request->getPostParams());
        unset($request);
    }

    /**
     * Verifies the corrent construction for $_GET values.
     * 
     * @test
     */
    public function checkQueryParams()
    {
        $request = new Request();
        $this->assertEquals($this->_get, $request->getQueryParams());
        unset($request);
    }

    /**
     * Verifies the corrent construction for $_ENV values.
     * @test
     */
    public function checkEnvironmentParams()
    {
        $request = new Request();
        $this->assertEquals($_ENV, $request->getEnvParams());
        unset($request);
    }

    /**
     * Verifies that files are well structured.
     * @test
     */
    public function checkCorrectFilesStruc()
    {
        $request = new Request();
        $this->assertEquals($this->_expectedFiles, $request->getFiles());
        unset($request);

        $_FILES = array(
            'upload' => array(
                'name' => 'file1.txt',
                'type' => 'text/plain',
                'tmp_name' => '/tmp/phpyzZxta',
                'error' => 0,
                'size' => 0
            )
        );

        $request = new Request();
        $this->assertEquals($_FILES, $request->getFiles());
        unset($request);
    }

    /**
     * Retrieves the authenticataion header from apache
     * @test
     */
    public function setDataFromServer()
    {
        $_SERVER['REQUEST_METHOD'] = Request::METHOD_HEAD;
        $_SERVER['HTTP_ACCEPT_CHARSET'] = 'iso-8859-1,*,utf-8';
        $_SERVER['SERVER_PROTOCOL'] = Request::VERSION_10;
        $_SERVER['CONTENT_LENGTH'] = 1254;
        $_SERVER['CONTENT_MD5'] = md5(1254);
        $_SERVER['HTTP_COOKIE'] = http_build_query(
            array('test' => 1)
        );
        $request = new Request();
        $this->assertEquals(Request::METHOD_HEAD, $request->method);
        $this->assertEquals($_SERVER, $request->getServerParams());
        $this->assertFalse($request->hasHeader('Cokie'));
        $this->assertTrue($request->hasHeader('Accept-Charset'));
        $this->assertTrue($request->hasHeader('Content-Length'));
        $this->assertTrue($request->hasHeader('Content-MD5'));
        $this->assertEquals(md5(1254), $request->getHeader('Content-MD5'));
        unset($request);
    }

}