<?php

/**
 * AbstractQueryParams
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm\Entity;

use Slick\Common\Base;

/**
 * AbstractQueryParams
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractQueryParams extends Base
{

	/**
	 * @readwrite
	 * @var array Where conditions
	 */
	protected $_where = array();

	/**
	 * @readwrite
	 * @var array Field list
	 */
	protected $_fields = array('*');

	/**
	 * @readwrite
	 * @var string The order field
	 */
	protected $_order = null;

	/**
	 * @readwrite
	 * @var string Order directoin clause
	 */
	protected $_direction = null;

	/**
	 * @readwrite
	 * @var string The limit clause
	 */
	protected $_limit = null;

	/**
	 * @readwrite
	 * @var string The page clause
	 */
	protected $_page = null;

}