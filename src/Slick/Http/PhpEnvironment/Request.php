<?php

/**
 * 
 */

namespace Slick\Http\PhpEnvironment;

use Slick\Http,
    Slick\Http\Exception;
use Zend\Uri\Http as HttpUri;

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
     * This is for tests only
     * @readwrite
     * @var string
     */
    protected $_stdIn = 'php://input';

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

    /**
     * Get raw request body
     *
     * @return string
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
     * Updates the server parameters
     * 
     * @param array $server Usualy the $_SERVER super global
     *
     * @return /Slick/Http/PhpEnvironment/Request A self instance for method
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
        if (isset($this->serverParams['SERVER_PROTOCOL'])
            && strpos($this->serverParams['SERVER_PROTOCOL'], self::VERSION_10) !== false
        ) {
            $this->setVersion(self::VERSION_10);
        }

        $this->_setUri($server);

        return $this;
    }

    /**
     * Returns the request URI for this request.
     * 
     * @return string HTTP requet URI
     */
    public function getRequestUri()
    {
        if ($this->_requestUri === null) {
            $this->_requestUri = $this->_detectRequestUri();
        }
        return $this->_requestUri;
    }

    /**
     * Sets the request URI basedo on environment settings.
     * 
     * @param array $server Usualy the $_SERVER super global
     */
    protected function _setUri($server)
    {
        // set URI
        $uri = new HttpUri();

        // URI scheme
        $scheme = (!empty($this->serverParams['HTTPS'])
                   && $this->serverParams['HTTPS'] !== 'off') ? 'https' : 'http';
        $uri->setScheme($scheme);

        // URI host & port
        $host = null;
        $port = null;

        // Set the host
        if ($this->hasHeader('Host')) {
            $host = $this->getHeader('Host', null);

            // works for regname, IPv4 & IPv6
            if (preg_match('#\:(\d+)$#', $host, $matches)) {
                $host = substr($host, 0, -1 * (strlen($matches[1]) + 1));
                $port = (int) $matches[1];
            }
        }

        if (!$host && isset($this->serverParams['SERVER_NAME'])) {
            $host = $this->serverParams['SERVER_NAME'];
            if (isset($this->serverParams['SERVER_PORT'])) {
                $port = (int) $this->serverParams['SERVER_PORT'];
            }
        }

        $uri->setHost($host);
        $uri->setPort($port);

        // URI path
        $requestUri = $this->getRequestUri();
        if (($qpos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $qpos);
        }

        $uri->setPath($requestUri);

        // URI query
        if (isset($this->serverParams['QUERY_STRING'])) {
            $uri->setQuery($this->serverParams['QUERY_STRING']);
        }

        $this->setUri($uri);
    }

    /**
     * Detect the base URI for the request
     *
     * Looks at a variety of criteria in order to attempt to autodetect a base
     * URI, including rewrite URIs, proxy URIs, etc.
     *
     * @return string
     */
    protected function _detectRequestUri()
    {
        $requestUri = null;
        $server     = $this->getServerParams();

        // Check this first so IIS will catch.
        $httpXRewriteUrl = isset($server['HTTP_X_REWRITE_URL']) ? 
            $server['HTTP_X_REWRITE_URL'] : null;
        if ($httpXRewriteUrl !== null) {
            $requestUri = $httpXRewriteUrl;
        }

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        $httpXOriginalUrl = isset($server['HTTP_X_ORIGINAL_URL']) ?
            $server['HTTP_X_ORIGINAL_URL'] : null;
        if ($httpXOriginalUrl !== null) {
            $requestUri = $httpXOriginalUrl;
        }

        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        $iisUrlRewritten = isset($server['IIS_WasUrlRewritten']) ?
            $server['IIS_WasUrlRewritten'] : null;

        $unencodedUrl    = isset($server['UNENCODED_URL']) ?
            $server['UNENCODED_URL'] : '';
        if ('1' == $iisUrlRewritten && '' !== $unencodedUrl) {
            return $unencodedUrl;
        }

        // HTTP proxy requests setup request URI with scheme and host [and port]
        // + the URL path, only use URL path.
        if (!$httpXRewriteUrl) {
            $requestUri = isset($server['REQUEST_URI']) ? 
                $server['REQUEST_URI'] : null;
        }

        if ($requestUri !== null) {
            return preg_replace('#^[^/:]+://[^/]+#', '', $requestUri);
        }

        // IIS 5.0, PHP as CGI.
        $origPathInfo =isset($server['ORIG_PATH_INFO']) ?
            $server['ORIG_PATH_INFO'] : null;
        if ($origPathInfo !== null) {
            $queryString = $server['QUERY_STRING'] ?
                $server['QUERY_STRING'] : '';
            if ($queryString !== '') {
                $origPathInfo .= '?' . $queryString;
            }
            return $origPathInfo;
        }

        return '/';
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