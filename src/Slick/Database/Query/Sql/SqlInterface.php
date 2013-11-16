<?php

/**
 * SqlInterface
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

/**
 * SqlInterface define a general SQL statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface SqlInterface
{

	/**
	 * Adds a condition to the where clause
	 * 
	 * @param array $conditions The condition to add.
	 * 
	 * @return \Slick\Database\Query\Sql\SqlInterface A self instance for
	 *  method call chains
	 */
	public function where($conditions);
}