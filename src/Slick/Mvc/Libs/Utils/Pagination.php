<?php

/**
 * Pagination utility
 *
 * @package   Slick\Mvc\Libs\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Libs\Utils;

use Slick\Common\Base;
use Slick\Filter\StaticFilter;
use Slick\Validator\StaticValidator;
use Zend\Http\PhpEnvironment\Request;

/**
 * Pagination utility
 *
 * @package   Slick\Mvc\Libs\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Request $request
 * @property int $offset
 * @property int $rowsPerPage
 */
class Pagination extends Base
{

    /**
     * @readwrite
     * @var int Total pages
     */
    protected $_pages = 0;

    /**
     * @readwrite
     * @var int Total records
     */
    protected $_total;

    /**
     * @readwrite
     * @var int current page index
     */
    protected $_current = 1;

    /**
     * @readwrite
     * @var int total rows per page
     */
    protected $_rowsPerPage = 12;

    /**
     * @readwrite
     * @var int First row to return
     */
    protected $_offset = 0;

    /**
     * @readwrite
     * @var Request
     */
    protected $_request;

    /**
     * Overrides the constructor to calculate the properties for current
     * pagination state.
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        parent::__construct($options);

        if ($this->request->getQuery('rows')) {
            $this->setRowsPerPage($this->request->getQuery('rows'));
        }

        if ($this->request->getQuery('page')) {
            $this->setCurrent($this->request->getQuery('page'));
        }


    }

    /**
     * Lazy loads request object
     *
     * @return Request
     */
    public function getRequest()
    {
        if (is_null($this->_request)) {
            $this->_request = new Request();
        }
        return $this->_request;
    }

    /**
     * Sets current page and calculates offset
     *
     * @param integer $value The number of the current page.
     *
     * @return Pagination A self instance for method chain calls
     */
    public function setCurrent($value)
    {
        if (!StaticValidator::isValid('number', $value)) {
            return $this;
        }

        $this->_current = StaticFilter::filter('number',$value);
        $this->_offset = ($this->_rowsPerPage * $this->_current) - 1;
        return $this;
    }

    /**
     * Sets the total rows to paginate.
     *
     * @param integer $value The rows total to set.
     *
     * @return Pagination A self instance for method chain calls
     */
    public function setTotal($value)
    {
        if (!StaticValidator::isValid('number', $value)) {
            return $this;
        }

        $this->_total = StaticFilter::filter('number',$value);
        $this->_pages = ceil($this->_total / $this->_rowsPerPage);
        return $this;
    }

    /**
     * Sets the total rows per page.
     *
     * @param integer $value The total rows per page to set.
     *
     * @return Pagination A self instance for method chain calls
     */
    public function setRowsPerPage($value)
    {
        if (!StaticValidator::isValid('number', $value)) {
            return $this;
        }

        $this->_rowsPerPage = StaticFilter::filter('number',$value);
        $this->_pages = ceil($this->_total / $this->_rowsPerPage);
        return $this;
    }

    /**
     * Creates a request query for the provided page.
     *
     * This method check the current request query in order to maintain the
     * other parameters unchanged and sets the 'page' parameter to the
     * provided page number.
     *
     * @param integer $page The page number to build the query on.
     *
     * @return string The query string to use in the pagination links.
     */
    public function pageUrl($page)
    {
        $params = $this->request->getQuery();

        if (isset($params['url']))
            unset($params['url']);
        if (isset($params['extension']))
            unset($params['extension']);
        $params['page'] = $page;
        return '?' . http_build_query($params);
    }
} 