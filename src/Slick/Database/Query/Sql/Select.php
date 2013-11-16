<?php

/**
 * Select
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql;

/**
 * Select is a representation of a SQL select statement
 *
 * @package   Slick\Database\Query\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends AbstractSql implements SelectInterface
{

	/**
	 * Returns a RecordList with all records result for this select.
	 * 
	 * @return \Slick\DataBase\RecordList
	 */
	public function all()
	{
		return $this->getQuery()
			->prepareSql($this)
			->execute($this->params);
	}
}