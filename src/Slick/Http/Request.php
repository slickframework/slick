<?php

/**
 * Request
 *
 * @package   Slick\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Http;

use Slick\Common\Base,
    Slick\Http\Exception;

/**
 * Request wrapps an HTTP request
 *
 * @package   Slick\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Request extends Base
{

    /**#@+
     * @const string METHOD constant names
     */
    const METHOD_OPTIONS  = 'OPTIONS';
    const METHOD_GET      = 'GET';
    const METHOD_HEAD     = 'HEAD';
    const METHOD_POST     = 'POST';
    const METHOD_PUT      = 'PUT';
    const METHOD_DELETE   = 'DELETE';
    const METHOD_TRACE    = 'TRACE';
    const METHOD_CONNECT  = 'CONNECT';
    const METHOD_PATCH    = 'PATCH';
    const METHOD_PROPFIND = 'PROPFIND';
    /**#@-*/

    /**
     * @readwrite
     * @var string The request method
     */
    protected $_method = self::METHOD_GET;

    /**#@+
     * @const string Version constant numbers
     */
    const VERSION_10 = '1.0';
    const VERSION_11 = '1.1';
    /**#@-*/

    /**
     * @readwrite
     * @var string HTTP protocol version 
     */
    protected $_version = self::VERSION_11;

    /**
     * @readwrite
     * @var string The request uri
     */
    protected $_uri = null;

    /**
     * @readwrite
     * @var array post parameters
     */
    protected $_postParams = array();

    /**
     * @readwrite
     * @var array Query parameters
     */
    protected $_queryParams = array();

    /**
     * @readwrite
     * @var string The request content body
     */
    protected $_content = null;

    /**
     * @readwrite
     * @var array Request headers
     */
    protected $_headers = array();

    /**
     * A factory for a Request object from a well-formed Http Request string
     *
     * @param  string $string
     * 
     * @return \Slick\Http\Request
     * 
     * @throws \Slick\Http\Exception\InvalidArgumentException
     */
    public static function fromString($string)
    {
        $request = new static();

        $lines = explode("\r\n", $string);

        // first line must be Method/Uri/Version string
        $matches = null;
        $methods = implode(
            '|',
            array(
                self::METHOD_OPTIONS, self::METHOD_GET, self::METHOD_HEAD,
                self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE,
                self::METHOD_TRACE, self::METHOD_CONNECT, self::METHOD_PATCH
            )
        );
        $regex     = '#^(?P<method>'
            . $methods
            . ')\s(?P<uri>[^ ]*)(?:\sHTTP\/(?P<version>\d+\.\d+)){0,1}#';

        $firstLine = array_shift($lines);
        if (!preg_match($regex, $firstLine, $matches)) {
            throw new Exception\InvalidArgumentException(
                'A valid request line was not found in the provided string'
            );
        }

        $request->setMethod($matches['method']);
        $request->setUri($matches['uri']);

        if (isset($matches['version'])) {
            $request->setVersion($matches['version']);
        }

        if (count($lines) == 0) {
            return $request;
        }

        $isHeader = true;
        $headers = $rawBody = array();
        while ($lines) {
            $nextLine = array_shift($lines);
            if ($nextLine == '') {
                $isHeader = false;
                continue;
            }
            if ($isHeader) {
                $headers[] = $nextLine;
            } else {
                $rawBody[] = $nextLine;
            }
        }

        if ($headers) {
            $request->headers = implode("\r\n", $headers);
        }

        if ($rawBody) {
            $request->setContent(implode("\r\n", $rawBody));
        }

        return $request;
    }

    /**
     * Set the HTTP version for this object, one of 1.0 or 1.1
     * (\Slick\Http\Request::VERSION_10, \Slick\Http\Request::VERSION_11)
     *
     * @param  string $version (Must be 1.0 or 1.1)
     * @return \Slick\Http\Request
     * @throws Exception\InvalidArgumentException
     */
    public function setVersion($version)
    {
        if ($version != self::VERSION_10 && $version != self::VERSION_11) {
            throw new Exception\InvalidArgumentException(
                'Not valid or not supported HTTP version: ' . $version
            );
        }
        $this->_version = $version;
        return $this;
    }

    /**
     * Returns the list of request headers.
     * 
     * @return array The request headers
     */
    public function getHeaders()
    {
        if (is_string($this->_headers)) {
            $this->_headers = $this->_headersFromString($this->_headers);
        }
        return $this->_headers;
    }

    /**
     * Checks if a header with provided ame exists.
     * 
     * @param  string  $name The header name to check
     * 
     * @return boolean True if header exists, false otherwise.
     */
    public function hasHeader($name)
    {
        $headers = $this->getHeaders();
        return isset($headers[$name]);
    }

    /**
     * Retrives the value of a given header
     * 
     * @param  String $name    The header name to check
     * @param  mixed  $default The default value id headers doesn't exists
     * 
     * @return string The header value
     */
    public function getHeader($name, $default = null)
    {
        $headers = $this->getHeaders();
        if (isset($headers[$name])) {
            return $headers[$name];
        }
        return $default;
    }

    /**
     * Sets a header
     * 
     * @param string $name  The header name to add
     * @param string $value The correspondent header value
     *
     * @return \Slick\Http\Request
     */
    public function setHeader($name, $value = null)
    {
        $this->_headers[$name] = $value;
        return $this;
    }

    /**
     * Is this an OPTIONS method request?
     *
     * @return bool
     */
    public function isOptions()
    {
        return ($this->method === self::METHOD_OPTIONS);
    }

    /**
     * Is this a PROPFIND method request?
     *
     * @return bool
     */
    public function isPropFind()
    {
        return ($this->method === self::METHOD_PROPFIND);
    }

    /**
     * Is this a GET method request?
     *
     * @return bool
     */
    public function isGet()
    {
        return ($this->method === self::METHOD_GET);
    }

    /**
     * Is this a HEAD method request?
     *
     * @return bool
     */
    public function isHead()
    {
        return ($this->method === self::METHOD_HEAD);
    }

    /**
     * Is this a POST method request?
     *
     * @return bool
     */
    public function isPost()
    {
        return ($this->method === self::METHOD_POST);
    }

    /**
     * Is this a PUT method request?
     *
     * @return bool
     */
    public function isPut()
    {
        return ($this->method === self::METHOD_PUT);
    }

    /**
     * Is this a DELETE method request?
     *
     * @return bool
     */
    public function isDelete()
    {
        return ($this->method === self::METHOD_DELETE);
    }

    /**
     * Is this a TRACE method request?
     *
     * @return bool
     */
    public function isTrace()
    {
        return ($this->method === self::METHOD_TRACE);
    }

    /**
     * Is this a CONNECT method request?
     *
     * @return bool
     */
    public function isConnect()
    {
        return ($this->method === self::METHOD_CONNECT);
    }

    /**
     * Is this a PATCH method request?
     *
     * @return bool
     */
    public function isPatch()
    {
        return ($this->method === self::METHOD_PATCH);
    }

    /**
     * Concerts the headers string to a valid request headers array
     *
     * @param string $string The headers string from request
     * 
     * @return array The headers array
     */
    protected function _headersFromString($string)
    {
        $headers = array();
        $lines = explode("\r\n", $string);

        // iterate the header lines, some might be continuations
        foreach ($lines as $line) {

            // check if a header name is present
            if (
                preg_match(
                    '/(?P<name>[a-zA-Z0-9_-]+):(?P<value>.*)/',
                    trim($line),
                    $matches
                )
            ) {
                $headers[$matches['name']] = trim($matches['value']);
                
            }
        }

        return $headers;
    }


}