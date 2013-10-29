<?php

/**
 * Response
 * 
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Http\PhpEnvironment;

use Slick\Http\Response as HttpResponse;

/**
 * Response HTTP message with values from PHP environment
 *
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Response extends HttpResponse
{

    /**
     * @read
     * @var boolean A flag for send headers state
     */
    protected $_headersSent = false;
    
    /**
     * @read
     * @var boolean A flag for send content state
     */
    protected $_contentSent = false;

    /**
     * Overrides the default constructor to set the correct HTTP version
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_version = $this->_detectVersion();
    }

    /**
     * Send HTTP response headers
     * 
     * @return \Slick\Http\PhpEnvironment\Response A self instance for
     *   method call chain.
     */
    public function sendHeaders()
    {
        if ($this->isHeadersSent()) {
            return $this;
        }

        $status = $this->getStatusLine();
        header($status);

        foreach ($this->_headers as $key => $value) {
            header("{$key}: {$value}", true);
        }

        $this->_headersSent = true;
        return $this;
    }

    /**
     * Send content
     *
     * @return \Slick\Http\PhpEnvironment\Response A self instance for
     *   method call chain.
     */
    public function sendContent()
    {
        if ($this->isContentSent()) {
            return $this;
        }

        echo $this->getContent();
        $this->_contentSent = true;
        return $this;
    }

    /**
     * Send HTTP response
     * 
     * @return \Slick\Http\PhpEnvironment\Response A self instance for
     *   method call chain.
     */
    public function send()
    {
        $this->sendHeaders()
            ->sendContent();
        return $this;
    }

    /**
     * Detect the current used protocol version.
     * If detection failed it falls back to version 1.0.
     *
     * @return string
     */
    protected function _detectVersion()
    {
        $version = self::VERSION_10;
        if (isset($_SERVER['SERVER_PROTOCOL'])
            && $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1'
        ) {
            $version = self::VERSION_11;
        }

        return $version;
    }
}