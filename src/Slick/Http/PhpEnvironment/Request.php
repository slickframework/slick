<?php

/**
 * 
 */

namespace Slick\Http\PhpEnvironment;

use Slick\Http,
    Slick\Http\Exception;

/**
 * 
 */
class Request extends Http\Request
{

    /**
     * @readwrite
     * @var array PHP server params ($_SERVER)
     */
    protected $_serverParams = array();

    /**
     * @readwrite
     * @var array PHP environment params ($_ENV)
     */
    protected $_envParams = null;

    /**
     * @readwrite
     * @var array Well structured upload files ($_FILES)
     */
    protected $_files = array();

    /**
     * Overrides the default constructor to retrive environment info
     * 
     * @param array $options Initialization options
     */
    public function __construct($options = array())
    {
        
        if ($_GET) {
            $this->_queryParams = $_GET;
        }
        if ($_POST) {
            $this->_postParams = $_POST;
        }

        parent::__construct($options);

        $this->_envParams = $_ENV;

        if ($_FILES) {
            // convert PHP $_FILES superglobal
            $this->setFiles($this->_mapPhpFiles());
        }

        $this->setServerParams($_SERVER);
    }

    public function setServerParams(array $server)
    {
        $this->_serverParams = $server;

        // This seems to be the way to get the Authorization header on Apache
        // @codeCoverageIgnoreStart
        if (function_exists('apache_request_headers')) {
            $apacheRequestHeaders = apache_request_headers();
            if (!isset($this->serverParams['HTTP_AUTHORIZATION'])) {
                if (isset($apacheRequestHeaders['Authorization'])) {
                    $this->_serverParams['HTTP_AUTHORIZATION'] 
                        = $apacheRequestHeaders['Authorization'];
                } elseif (isset($apacheRequestHeaders['authorization'])) {
                    $this->serverParams['HTTP_AUTHORIZATION'] 
                        = $apacheRequestHeaders['authorization'];
                }
            }
        }
        // @codeCoverageIgnoreEnd

        //set headers
        $this->_parseServerHeaders($server);

        // set method
        if (isset($this->serverParams['REQUEST_METHOD'])) {
            $this->setMethod($this->serverParams['REQUEST_METHOD']);
        }

        // set HTTP version
        if (isset($this->serverParams['SERVER_PROTOCOL'])
            && strpos($this->serverParams['SERVER_PROTOCOL'], self::VERSION_10) !== false
        ) {
            $this->setVersion(self::VERSION_10);
        }
    }

    protected function _parseServerHeaders($server)
    {
        // set headers
        $headers = array();

        foreach ($server as $key => $value) {
            if ($value && strpos($key, 'HTTP_') === 0) {
                if (strpos($key, 'HTTP_COOKIE') === 0) {
                    // Cookies are handled using the $_COOKIE superglobal
                    continue;
                }
                $name = strtr(substr($key, 5), '_', ' ');
                $name = strtr(ucwords(strtolower($name)), ' ', '-');
            } elseif ($value && strpos($key, 'CONTENT_') === 0) {
                $name = substr($key, 8); // Content-
                $name = 'Content-' 
                    . (($name == 'MD5') ? $name : ucfirst(strtolower($name)));
            } else {
                continue;
            }

            $headers[$name] = $value;
        }

        $this->_headers = array_merge($headers, $this->_headers);
    }

    /**
     * Convert PHP superglobal $_FILES into more sane parameter=value structure
     * This handles form file input with brackets (name=files[])
     *
     * @return array
     */
    protected function _mapPhpFiles()
    {
        $files = array();
        foreach ($_FILES as $fileName => $fileParams) {
            $files[$fileName] = array();
            foreach ($fileParams as $param => $data) {
                if (!is_array($data)) {
                    $files[$fileName][$param] = $data;
                } else {
                    foreach ($data as $i => $v) {
                        $this->_mapPhpFileParam(
                            $files[$fileName],
                            $param,
                            $i,
                            $v
                        );
                    }
                }
            }
        }

        return $files;
    }

    /**
     * @param array        $array
     * @param string       $paramName
     * @param int|string   $index
     * @param string|array $value
     */
    protected function _mapPhpFileParam(&$array, $paramName, $index, $value)
    {
        if (!is_array($value)) {
            $array[$index][$paramName] = $value;
        } else {
            foreach ($value as $i => $v) {
                $this->_mapPhpFileParam($array[$index], $paramName, $i, $v);
            }
        }
    }
}