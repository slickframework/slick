<?php

/**
 * Response
 *
 * @package   Slick\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Http;

use Slick\Http\Exception;

/**
 * General HTTP response
 *
 * @package   Slick\Http
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * 
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class Response extends Message
{

     /**#@+
     * @const int Status codes
     */
    const STATUS_CODE_CUSTOM = 0;
    const STATUS_CODE_100 = 100;
    const STATUS_CODE_101 = 101;
    const STATUS_CODE_102 = 102;
    const STATUS_CODE_200 = 200;
    const STATUS_CODE_201 = 201;
    const STATUS_CODE_202 = 202;
    const STATUS_CODE_203 = 203;
    const STATUS_CODE_204 = 204;
    const STATUS_CODE_205 = 205;
    const STATUS_CODE_206 = 206;
    const STATUS_CODE_207 = 207;
    const STATUS_CODE_208 = 208;
    const STATUS_CODE_300 = 300;
    const STATUS_CODE_301 = 301;
    const STATUS_CODE_302 = 302;
    const STATUS_CODE_303 = 303;
    const STATUS_CODE_304 = 304;
    const STATUS_CODE_305 = 305;
    const STATUS_CODE_306 = 306;
    const STATUS_CODE_307 = 307;
    const STATUS_CODE_400 = 400;
    const STATUS_CODE_401 = 401;
    const STATUS_CODE_402 = 402;
    const STATUS_CODE_403 = 403;
    const STATUS_CODE_404 = 404;
    const STATUS_CODE_405 = 405;
    const STATUS_CODE_406 = 406;
    const STATUS_CODE_407 = 407;
    const STATUS_CODE_408 = 408;
    const STATUS_CODE_409 = 409;
    const STATUS_CODE_410 = 410;
    const STATUS_CODE_411 = 411;
    const STATUS_CODE_412 = 412;
    const STATUS_CODE_413 = 413;
    const STATUS_CODE_414 = 414;
    const STATUS_CODE_415 = 415;
    const STATUS_CODE_416 = 416;
    const STATUS_CODE_417 = 417;
    const STATUS_CODE_418 = 418;
    const STATUS_CODE_422 = 422;
    const STATUS_CODE_423 = 423;
    const STATUS_CODE_424 = 424;
    const STATUS_CODE_425 = 425;
    const STATUS_CODE_426 = 426;
    const STATUS_CODE_428 = 428;
    const STATUS_CODE_429 = 429;
    const STATUS_CODE_431 = 431;
    const STATUS_CODE_500 = 500;
    const STATUS_CODE_501 = 501;
    const STATUS_CODE_502 = 502;
    const STATUS_CODE_503 = 503;
    const STATUS_CODE_504 = 504;
    const STATUS_CODE_505 = 505;
    const STATUS_CODE_506 = 506;
    const STATUS_CODE_507 = 507;
    const STATUS_CODE_508 = 508;
    const STATUS_CODE_511 = 511;
    /**#@-*/

    /**
     * @read
     * @var array Recommended Reason Phrases
     */
    protected $_standardPhrases = array(
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    );

    /**
     * @readwrite
     * @var int Status code
     */
    protected $_statusCode = 200;

    /**
     * @readwrite
     * @var string|null Null means it will be looked up from the 
     *   $_standardPhrases list above
     *
     * @see  \Slick\Http\Response::$_standardPhrases
     */
    protected $_reasonPhrase = null;

    /**
     * A factory for a Response object from a well-formed Http Response string
     *
     * @param  string $string A well-formed Http Response string
     * 
     * @return \Slick\Http\Response A new Response object from given
     *   message string
     * 
     * @throws \Slick\Http\Exception\InvalidArgumentException If the string
     *   provided as argument is not a valid HTTP response message.
     *
     * @SuppressWarnings(PHPMD)
     */
    public static function fromString($string)
    {
        $lines = explode("\r\n", $string);
        if (!is_array($lines) || count($lines) == 1) {
            $lines = explode("\n", $string);
        }

        $firstLine = array_shift($lines);

        $response = new static();

        $regex   = 
            '/^HTTP\/(?P<ver>1\.[01]) (?P<sts>\d{3})(?:[ ]+(?P<re>.*))?$/';
        $matches = array();
        if (!preg_match($regex, $firstLine, $matches)) {
            throw new Exception\InvalidArgumentException(
                'A valid response status line was not found in the provided '
                .'string'
            );
        }

        $response->version = $matches['ver'];
        $response->setStatusCode($matches['sts']);
        $response->setReasonPhrase(
            (isset($matches['re']) ? $matches['re'] : '')
        );

        if (count($lines) == 0) {
            return $response;
        }

        $isHeader = true;
        $headers = $content = array();

        while ($lines) {
            $nextLine = array_shift($lines);

            if ($isHeader && $nextLine == '') {
                $isHeader = false;
                continue;
            }
            if ($isHeader) {
                $headers[] = $nextLine;
            } else {
                $content[] = $nextLine;
            }
        }

        if ($headers) {
            $response->headers = implode("\r\n", $headers);
        }

        if ($content) {
            $response->setContent(implode("\r\n", $content));
        }

        return $response;
    }

    /**
     * Set HTTP status code and (optionally) message
     *
     * @param  int $code The HTTP status code (Must be numeric)
     * 
     * @return \Slick\Http\Response A self instance for method call chain
     * 
     * @throws \Slick\Http\Exception\InvalidArgumentException If the status
     *   is not a valid numeric value
     */
    public function setStatusCode($code)
    {
        if (!is_numeric($code)) {
            $code = is_scalar($code) ? $code : gettype($code);
            throw new Exception\InvalidArgumentException(
                sprintf('Invalid status code provided: "%s"', $code)
            );
        }
        $this->_statusCode = (int) $code;
        return $this;
    }

    /**
     * Set the response status message
     * 
     * @param string $reasonPhrase Response status message string
     * 
     * @return \Slick\Http\Response A self instance for method call chain
     */
    public function setReasonPhrase($reasonPhrase)
    {
        $this->_reasonPhrase = trim($reasonPhrase);
        return $this;
    }

    /**
     * Get HTTP status message
     *
     * @return string The current response status message
     */
    public function getReasonPhrase()
    {
        if ($this->_reasonPhrase == null) {
            return $this->_standardPhrases[$this->_statusCode];
        }
        return $this->_reasonPhrase;
    }

    /**
     * Does the status code indicate a client error?
     *
     * @return bool True if status code is between 400 (inclusive) and 500,
     *  for all other codes it will return false
     */
    public function isClientError()
    {
        $code = $this->getStatusCode();
        return ($code < 500 && $code >= 400);
    }

    /**
     * Is the request forbidden due to ACLs?
     *
     * @return bool rue if status code is 403, for all other codes
     *   it will return false
     */
    public function isForbidden()
    {
        return (403 == $this->getStatusCode());
    }

    /**
     * Is the current status "informational"?
     *
     * @return bool True if status code is between 100 (inclusive) and 200,
     *  for all other codes it will return false
     */
    public function isInformational()
    {
        $code = $this->getStatusCode();
        return ($code >= 100 && $code < 200);
    }

    /**
     * Does the status code indicate the resource is not found?
     *
     * @return bool bool True if status code is 404, for all other codes
     *   it will return false
     */
    public function isNotFound()
    {
        return (404 === $this->getStatusCode());
    }

    /**
     * Do we have a normal, OK response?
     *
     * @return bool bool rue if status code is 200, for all other codes
     *   it will return false
     */
    public function isOk()
    {
        return (200 === $this->getStatusCode());
    }

    /**
     * Does the status code reflect a server error?
     *
     * @return bool True if status code is between 500 (inclusive) and 600,
     *  for all other codes it will return false
     */
    public function isServerError()
    {
        $code = $this->getStatusCode();
        return (500 <= $code && 600 > $code);
    }

    /**
     * Do we have a redirect?
     *
     * @return bool True if status code is between 300 (inclusive) and 400,
     *  for all other codes it will return false
     */
    public function isRedirect()
    {
        $code = $this->getStatusCode();
        return (300 <= $code && 400 > $code);
    }

    /**
     * Was the response successful?
     *
     * @return bool True if status code is between 200 (inclusive) and 300,
     *  for all other codes it will return false
     */
    public function isSuccess()
    {
        $code = $this->getStatusCode();
        return (200 <= $code && 300 > $code);
    }

    /**
     * Render the status line header
     *
     * @return string The status line header
     */
    public function renderStatusLine()
    {
        $status = sprintf(
            'HTTP/%s %d %s',
            $this->getVersion(),
            $this->getStatusCode(),
            $this->getReasonPhrase()
        );
        return trim($status);
    }

    /**
     * Returns the formatted full Http response message string
     *
     * @return string Http response message as string
     */
    public function toString()
    {
        $str  = $this->renderStatusLine() . "\r\n";
        $str .= $this->_headersToString();
        $str .= "\r\n";
        $str .= $this->getContent();
        return $str;
    }
}