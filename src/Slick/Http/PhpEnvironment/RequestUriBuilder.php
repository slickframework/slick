<?php

/**
 * RequestUriBuilder
 *
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Http\PhpEnvironment;

use Slick\Common\Base;
use Zend\Uri\Http as HttpUri;

/**
 * RequestUriBuilder is a helper class to set the URI based on $_SERVER values
 *
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RequestUriBuilder extends Base
{

    /**
     * @readwrite
     * @var \Zend\Uri\Http The resultant URI
     */
    protected $_uri = null;

    /**
     * @readwrite
     * @var array $_SERVER values
     */
    protected $_serverParams = array();

    /**
     * @readwrite
     * @var \Slick\Http\PhpEnvironment\Request
     */
    protected $_request = null;

    /**
     * Returns the URI from $_SERVER values
     * 
     * @return \Zend\Uri\Http A requet URI
     */
    public function getUri()
    {
        if (is_null($this->_uri)) {
            $this->_setUri();
        }
        return $this->_uri;
    }

    protected function _setUri()
    {
        $this->_uri = new HttpUri();

        $this->_uri->setScheme($this->_getScheme());

        $this->_setHostAndPort();

        $this->_uri->setPath($this->_getPath());

        // URI query
        if (isset($this->serverParams['QUERY_STRING'])) {
            $this->_uri->setQuery($this->serverParams['QUERY_STRING']);
        }
    }

    protected function _getScheme()
    {
        // URI scheme
        $server = $this->serverParams;
        $scheme = (!empty($server['HTTPS'])
                   && $server['HTTPS'] !== 'off') ? 'https' : 'http';
        return $scheme;
    }

    protected function _setHostAndPort()
    {
        // URI host & port
        $host = null;
        $port = null;

        // Set the host
        if ($this->request->hasHeader('Host')) {
            $host = $this->request->getHeader('Host', null);

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

        $this->_uri->setHost($host);
        $this->_uri->setPort($port);
    }

    protected function _getPath()
    {
        // URI path
        $requestUri = $this->request->getRequestUri();
        if (($qpos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $qpos);
        }
        return $requestUri;
    }
}