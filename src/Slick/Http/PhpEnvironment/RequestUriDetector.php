<?php

/**
 * RequestUriDetector
 * 
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Http\PhpEnvironment;

use Slick\Common\Base;

/**
 * RequestUriDetector is an utility class for URI detection for HTTP responses
 *
 * @package   Slick\Http\PhpEnvironment
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class RequestUriDetector extends Base
{
    
    /**
     * @readwrite
     * @var string The detected request URI
     */
    protected $_requestUri = null;

    /**
     * @readwrite
     * @var array The $_SERVER parameters.
     */
    protected $_serverParams = array();

    /**
     * @read
     * @var boolean A flag for a rewrite request
     */
    protected $_xRewriteUrl = false;

    /**
     * Returns a detected request URI
     * 
     * @return string The detected request URI or NULL if not detected.
     */
    public function getRequestUri()
    {
        if (is_null($this->_requestUri)) {
            $this->_detecteRequestUri();
        }
        return $this->_requestUri;
    }

    /**
     * Detect the base URI for the request
     *
     * Looks at a variety of criteria in order to attempt to autodetect a base
     * URI, including rewrite URIs, proxy URIs, etc.
     *
     * @return string Detected request URI
     */
    protected function _detecteRequestUri()
    {
        $server = $this->getServerParams();

        // IIS7 with URL Rewrite
        $unencodedUrl = $this->_checkUrlRewritten();
        if ($unencodedUrl !== false) {
            $this->_requestUri = $unencodedUrl;
            return;
        }

        // Check this first so IIS will catch.
        $this->_checkXRewriteUrl();

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        $this->_checkXOriginalUrl();

        // HTTP proxy requests setup request URI with scheme and host, and port
        // + the URL path, only use URL path.
        if (!$this->_xRewriteUrl) {
            $this->_requestUri = isset($server['REQUEST_URI']) ? 
                $server['REQUEST_URI'] : null;
        }

        // The request URI was found. Fix it and reutn
        if ($this->_requestUri !== null) {
            $this->_requestUri = preg_replace(
                '#^[^/:]+://[^/]+#',
                '',
                $this->_requestUri
            );
            return;
        }

        // IIS 5.0, PHP as CGI.
        $originPathInfo = $this->_checkOriginPathInfo();
        if ($originPathInfo !== false) {
            $this->_requestUri =  $originPathInfo;
            return;
        }

        $this->_requestUri = '/';
    }

    /**
     * Check X Rewrite URL on IIS
     */
    protected function _checkXRewriteUrl()
    {
        $server = $this->getServerParams();

        // Check this first so IIS will catch.
        $httpXRewriteUrl = isset($server['HTTP_X_REWRITE_URL']) ? 
            $server['HTTP_X_REWRITE_URL'] : null;
        if ($httpXRewriteUrl !== null) {
            $this->_requestUri = $httpXRewriteUrl;
            $this->_xRewriteUrl = true;
        }
    }

    /**
     * Check for IIS 7.0 or later with ISAPI_Rewrite
     */
    protected function _checkXOriginalUrl()
    {
        $server = $this->getServerParams();

        // Check for IIS 7.0 or later with ISAPI_Rewrite
        $httpXOriginalUrl = isset($server['HTTP_X_ORIGINAL_URL']) ?
            $server['HTTP_X_ORIGINAL_URL'] : null;
        if ($httpXOriginalUrl !== null) {
            $this->_requestUri = $httpXOriginalUrl;
        }
    }

    /**
     * IIS7 with URL Rewrite: make sure we get the unencoded url
     *
     * @return string|boolean The requested uri or boolean false.
     */
    protected function _checkUrlRewritten()
    {
        $server = $this->getServerParams();

        // IIS7 with URL Rewrite: make sure we get the unencoded url
        // (double slash problem).
        $iisUrlRewritten = isset($server['IIS_WasUrlRewritten']) ?
            $server['IIS_WasUrlRewritten'] : null;

        $unencodedUrl    = isset($server['UNENCODED_URL']) ?
            $server['UNENCODED_URL'] : '';
        if ('1' == $iisUrlRewritten && '' !== $unencodedUrl) {
            return $unencodedUrl;
        }
        return false;
    }

    /**
     * IIS 5.0, PHP as CGI.
     * 
     * @return string|boolean The requested uri or boolean false.
     */
    protected function _checkOriginPathInfo()
    {
        $server = $this->getServerParams();

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
        return false;
    }
}