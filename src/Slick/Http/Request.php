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

use Slick\Http\Exception;
use Zend\Uri\Http as HttpUri,
    Zend\Uri\Exception as UriException;

/**
 * General HTTP request
 *
 * @package   Slick\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * 
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class Request extends Message
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
     * A factory for a Request object from a well-formed Http Request string
     *
     * @param  string $string A well-formed Http Request message string
     * 
     * @return \Slick\Http\Request A new Request object from given
     *   message string
     * 
     * @throws \Slick\Http\Exception\InvalidArgumentException If the string
     *   provided as argument is not a valid HTTP request message.
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
     * Set the URI/URL for this request
     * 
     * This can be a string or an instance of Zend\Uri\Http
     *
     * @param  string|HttpUri $uri
     * 
     * @return Request
     * 
     * @throws \Slick\Http\Exception\InvalidArgumentException
     */
    public function setUri($uri)
    {
        if (is_string($uri)) {
            try {
                $uri = new HttpUri($uri);
            } catch (UriException\InvalidUriPartException $e) {
                throw new Exception\InvalidArgumentException(
                    sprintf('Invalid URI passed as string (%s)', (string) $uri),
                    $e->getCode(),
                    $e
                );
            }
        } elseif (!($uri instanceof HttpUri)) {
            throw new Exception\InvalidArgumentException(
                'URI must be an instance of Zend\Uri\Http or a string'
            );
        }
        $this->_uri = $uri;

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
     * Is the request a Javascript XMLHttpRequest?
     *
     * Should work with Prototype/Script.aculo.us, possibly others.
     *
     * @return bool
     */
    public function isXmlHttpRequest()
    {
        if ($this->hasHeader('X-Requested-With')) {
            return $this->getHeader('X-Requested-With') == 'XMLHttpRequest';
        }
        return false;
    }

    /**
     * Is this a Flash request?
     *
     * @return bool
     */
    public function isFlashRequest()
    {
        if ($this->hasHeader('User-Agent')) {
            return (boolean) stristr($this->getHeader('User-Agent'), ' flash');
        }
        return false;
    }

    /**
     * Return the formatted request line (first line) for this http request
     *
     * @return string Formated request line for this http request
     */
    public function renderRequestLine()
    {
        return $this->method . ' ' . $this->uri . ' HTTP/' . $this->version;
    }

    /**
     * Returns the formatted full Http request message string
     * 
     * @return string Http request message as string
     */
    public function toString()
    {
        $str = $this->renderRequestLine() . "\r\n";
        $str .= $this->_headersToString();
        $str .= "\r\n";
        $str .= $this->getContent();
        return $str;
    }

}