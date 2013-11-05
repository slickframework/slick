<?php

/**
 * RequestBaseUrl
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
 * RequestBaseUrl is used to detect the base url of a request
 */
class RequestBaseUrl extends Base
{
    /**
     * @readwrite
     * @var array PHP server params ($_SERVER)
     */
    protected $_serverParams = array();
    
    /**
     * Use server params trait
     */
    use ServerParams;

    /**
     * @readwrite
     * @var string Base URL of the application.
     */
    protected $_baseUrl = null;

    /**
     * @readwrite
     * @var string Base URL of the application.
     */
    protected $_basePath = null;

    /**
     * @readwrite
     * @var \Slick\Http\PhpEnvironment\Request
     */
    protected $_request = null;

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
     * Autodetect the base path of the request
     *
     * Uses several criteria to determine the base path of the request.
     *
     * @return string Detected base path for this request
     */
    protected function _detectBasePath()
    {
        $scriptName = $this->getServerParam('SCRIPT_FILENAME');
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

    /**
     * Detect base url form server values.
     * 
     * @return string The detected base URL
     */
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
        $requestUri = $this->request->getRequestUri();

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
}