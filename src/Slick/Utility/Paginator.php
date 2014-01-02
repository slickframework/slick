<?php

/**
 * Paginator
 *
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Utility;

use Slick\Common\Base;

/**
 * Paginator
 *
 * Paginator is a simple utility that will help you present paginators
 * on a web page by doing all the math for you. All you need to have
 * a paginator running is the total rows you have and the number of
 * rows displayed per page.
 * 
 * @package   Slick\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Paginator extends Base
{

	/**
	 * @readwrite
	 * @var integer The total rows you have
	 */
	protected $_totalRows = 0;

	/**
	 * @readwrite
	 * @var integer The total rows to display per page
	 */
	protected $_rowsPerPage = 12;

	/**
	 * @readwrite
	 * @var integer The total pages for this data set
	 */
	protected $_pages = 0;

	/**
	 * @readwrite
	 * @var integer The current page do display
	 */
	protected $_currentPage = 1;

	/**
	 * @readwrite
	 * @var integer The offset of the first row to return
	 */
	protected $_offset = 0;

	/**
	 * Paginator constructor
	 *
	 * Overrides the constructor to calculate the properties for current
	 * paginator state.
	 * 
	 * @param array $options A key/value pair for default values.
	 * 
	 * @see \Slick\Common\Base
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		if ($this->request->param('page')) {
			$this->currentPage = $this->request->param('page');
		}
	}

	/**
	 * Sets current page and calculates offest
	 * 
	 * @param integer $value The number of the current page.
	 *
	 * @return Paginator A self instance for method chain calls
	 */
	public function setCurrentPage($value)
	{
		if (intval($value) <= 0) {
			return $this;
		}

		$this->_currentPage = $value;
		$this->_offset = $this->rowsPerPage * ($this->_currentPage - 1);
		return $this;
	}

	/**
	 * Sets the total rows to paginate.
	 * 
	 * @param integer $value The rows total to set.
	 *
	 * @return Paginator A self instance for method chain calls
	 */
	public function setTotalRows($value)
	{
		if (intval($value) <= 0) {
			return $this;
		}

		$this->_totalRows = $value;
		$this->_pages = ceil($this->_totalRows / $this->_rowsPerPage);
		return $this;
	}

	/**
	 * Sets the total rows per page.
	 * 
	 * @param integer $value The total rows per page to set.
	 *
	 * @return Paginator A self instance for method chain calls
	 */
	public function setRowsPerPage($value)
	{
		if (intval($value) <= 0) {
			return $this;
		}

		$this->_rowsPerPage = $value;
		$this->_pages = ceil($this->_totalRows / $this->_rowsPerPage);
		return $this;
	}

	/**
	 * Creates a request query for the provided page.
	 *
	 * This method check the current request query in order to mantain the
	 * other parameters unchanged and sets the 'page' parameter to the
	 * provided page number.
	 * 
	 * @param integer $page The page number to build the query on.
	 * 
	 * @return string The query string to use in the paginator links.
	 */
	public function pageUrl($page)
	{
		$params = $this->request->params;

		unset($params['url']);
		unset($params['extension']);
		$params['page'] = $page;
		return '?' . http_build_query($params);
	}

}