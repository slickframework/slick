<?php

/**
 * Request
 * 
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Http\PhpEnvironment;

use Slick\Http,
    Slick\Http\Exception;
use Zend\Uri\Http as HttpUri;

/**
 * Request HTTP message with values from PHP environment
 *
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
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
     * @readwrite
     * @var string Actual request URI, independent of the platform.
     */
    protected $_requestUri;

    /**
     * @readwrite
     * @var string Base URL of the application.
     */
    protected $_baseUrl;

    /**
     * @readwrite
     * @var string Base Path of the application.
     */
    protected $_basePath;

    /**
     * 
     * @readwrite
     * @var string This is for reading the request from in
     */
    protected $_stdIn = 'php://input';

    /**
     * @read
     * @var \Slick\Http\PhpEnvironment\RequestUriDetector
     */
    protected $_requestUriDetector = null;

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

        $this->_requestUriDetector =
            new RequestUriDetector(array('serverParams' => $_SERVER));

        $this->_envParams = $_ENV;

        if ($_FILES) {
            // convert PHP $_FILES superglobal
            $this->setFiles($this->_mapPhpFiles());
        }

        $this->setServerParams($_SERVER);

        
    }

    /**
     * Get raw request body
     *
     * @return string The content portion form response message 
     */
    public function getContent()
    {
        if (empty($this->_content)) {
            $requestBody = file_get_contents($this->_stdIn);
            if (strlen($requestBody) > 0) {
                $this->_content = $requestBody;
            }
        }

        return $this->_content;
    }

     /**
     * Get the base URL.
     *
     * @return string The base URL (path/file.html)
     */
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->setBaseUrl($this->_detectBaseUrl());
        }
        return $this->_baseUrl;
    }

    /**
     * Set the base URL.
     *
     * @param  string $baseUrl The base URL for this message
     * 
     * @return \Slick\Http\PhpEnvironment\Request A self instance for method
     *   call chain.
     */
    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * Set the base path.
     *
     * @param  string $basePath The base path for this message.
     * 
     * @return \Slick\Http\PhpEnvironment\Request A self instance for method
     *   call chain.
     */
    public function setBasePath($basePath)
    {
        $this->_basePath = rtrim($basePath, '/');
        return $this;
    }

    /**
     * Get the base path.
     *
     * @return string This response base path
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $this->setBasePath($this->_detectBasePath());
        }

        return $this->_basePath;
    }

    /**
     * Updates the server parameters
     * 
     * @param array $server Usualy the $_SERVER super global
     *
     * @return \Slick\Http\PhpEnvironment\Request A self instance for method
     *   call chains.
     */
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
        $srv = $this->serverParams;
        if (isset($srv['SERVER_PROTOCOL'])
            && strpos($srv['SERVER_PROTOCOL'], self::VERSION_10) !== false
        ) {
            $this->setVersion(self::VERSION_10);
        }

        $builder = new RequestUriBuilder(
            array(
                'request' => $this,
                'serverParams' => $server
            )
        );
        $this->setUri($builder->getUri());

        return $this;
    }

    /**
     * Returns an element from $_SERVER params.
     * 
     * @param  string $name The element name to retrieve
     * 
     * @return string The server value for the given element name or
     *  null if elemente is not found.
     */
    public function getServerParam($name)
    {
        $server = $this->getServerParams();
        if (isset($server[$name])) {
            return $server[$name];
        }
        return null;
    }

    /**
     * Returns the request URI for this request.
     * 
     * @return string HTTP requet URI
     */
    public function getRequestUri()
    {
        if ($this->_requestUri === null) {
            $this->_requestUri = $this->_requestUriDetector->getRequestUri();
        }
        return $this->_requestUri;
    }

    /**
     * Retrieve headers from global server var.
     * 
     * @param array $server Usualy the $_SERVER super global
     */
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
     * 
     * This handles form file input with brackets (name=files[])
     *
     * @return array A more readable/workable upload files estructure
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
     * Iterates over a given array to change its structure.
     *
     * This is used in \Slick\Http\PhpEnvironment::_mapPhpFiles() method
     * 
     * @param array        $array
     * @param string       $paramName
     * @param int|string   $index
     * @param string|array $value
     *
     * @see  \Slick\Http\PhpEnvironment::_mapPhpFiles()
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

    /**
     * Auto-detect the base path from the request environment
     *
     * Uses a variety of criteria in order to detect the base URL of the request
     * (i.e., anything additional to the document root).
     *
     *
     * @return string detected base URL for this request
     */
    protected function _detectBaseUrl()
    {
        $baseUrl = $this->_getBaseUrlFromServer();


        // Does the base URL have anything in common with the request URI?
        $requestUri = $this->getRequestUri();

        // Full base URL matches.
        if (0 === strpos($requestUri, $baseUrl)) {
            return $baseUrl;
        }

        // Directory portion of base path matches.
        $baseDir = str_replace('\\', '/', dirname($baseUrl));
        if (0 === strpos($requestUri, $baseDir)) {
            return $baseDir;
        }

        $truncatedRequestUri = $requestUri;

        if (false !== ($pos = strpos($requestUri, '?'))) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);

        // No match whatsoever
        if (empty($basename)
            || false === strpos($truncatedRequestUri, $basename)
        ) {
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of the base path. $pos !== 0 makes sure it is not matching a
        // value from PATH_INFO or QUERY_STRING.
        if (strlen($requestUri) >= strlen($baseUrl)
            && (false !== ($pos = strpos($requestUri, $baseUrl)) && $pos !== 0)
        ) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return $baseUrl;
    }

    /**
     * Autodetect the base path of the request
     *
     * Uses several criteria to determine the base path of the request.
     *
     * @return string Detected base path for this request
     */
    protected function _detectBasePath()
    {
        $scriptName = isset($this->serverParams['SCRIPT_FILENAME']) ?
            $this->serverParams['SCRIPT_FILENAME'] : '';
        $filename = basename($scriptName);
        $baseUrl  = $this->getBaseUrl();

        // Empty base url detected
        if ($baseUrl === '') {
            return '';
        }

        // basename() matches the script filename; return the directory
        if (basename($baseUrl) === $filename) {
            return str_replace('\\', '/', dirname($baseUrl));
        }

        // Base path is identical to base URL
        return $baseUrl;
    }

    protected function _getBaseUrlFromServer()
    {
        $baseUrl        = '';

        $filename       = $this->getServerParam('SCRIPT_FILENAME');
        $scriptName     = $this->getServerParam('SCRIPT_NAME');
        $phpSelf        = $this->getServerParam('PHP_SELF');
        $origScriptName = $this->getServerParam('ORIG_SCRIPT_NAME');

        if ($scriptName !== null && basename($scriptName) === $filename) {
            $baseUrl = $scriptName;
        } elseif ($phpSelf !== null && basename($phpSelf) === $filename) {
            $baseUrl = $phpSelf;
        } elseif ($origScriptName !== null
            && basename($origScriptName) === $filename
        ) {
            // 1and1 shared hosting compatibility.
            $baseUrl = $origScriptName;
        } else {
            // Backtrack up the SCRIPT_FILENAME to find the portion
            // matching PHP_SELF.

            $baseUrl  = '/';
            $basename = basename($filename);
            if ($basename) {
                $path     = ($phpSelf ? trim($phpSelf, '/') : '');
                $baseUrl .= substr($path, 0, strpos($path, $basename));
                $baseUrl .= $basename;
            }
        }

        return $baseUrl;
    }
}
