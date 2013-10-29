<?php

/**
 * HTTP Message
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
 * Represents an HTTP message, used in request and response.
 *
 * @package   Slick\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @see       \Slick\Http\Response
 * @see       \Slick\Http\Request
 */
abstract class Message extends Base
{


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
     * @var array Request headers
     */
    protected $_headers = array();

    /**
     * @readwrite
     * @var string The request content body
     */
    protected $_content = null;

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
     * Set the response headers list
     * 
     * @param string|array $headers The string from HTTP request or a array with
     *   the list of headers for this response.
     *
     * @return \Slick\Http\Response A sefl instance for method call chains.
     *
     * @throws \Slick\Http\Exception\InvalidArgumentException If the param
     *   isn't a string or an array with header values.
     */
    public function setHeaders($headers)
    {
        if (is_string($headers)) {
            $headers = $this->_headersFromString($headers);
        } else if (!is_array($headers)) {
            throw new Exception\InvalidArgumentException(
                "Invalid headers provided. It must be a string or an array " .
                "with header values."
            );
            
        }
        $this->_headers = $headers;
        return $this;
    }

    /**
     * Converts the list of headers to be printed out as string.
     * 
     * @return string
     */
    protected function _headersToString()
    {
        $headers = '';
        foreach ($this->_headers as $key => $value) {
            $headers .= "{$key}: {$value}\r\n";
        }
        return $headers;
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