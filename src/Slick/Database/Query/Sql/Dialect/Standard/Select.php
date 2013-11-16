<?php

/**
 * Select
 * 
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\Standard;

use Slick\Common\Base;

/**
 * Select
 *
 * @package   Slick\Database\Query\Sql\Dialect
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Select extends Base
{
	/**
	 * @readwrite
	 * @var \Slick\Database\Query\SqlInterface
	 */
	protected $_sql;

	protected $_select = <<<EOT
SELECT <fields><joinFields> FROM <tableName>
<joins>
<where>
<groupBy>
<having>
<orderBy>
<limit>
EOT;

	/**
	 * Returns the SQL query string for current Select SQL Object
	 * 
	 * @return String The SQL query string
	 */
	public function getStatement()
	{
		return trim(
			str_replace(
				array(
					'<fields>', '<joinFields>', '<tableName>',
					'<joins>', '<where>', '<groupBy>', '<having>',
					'<orderBy>', '<limit>'
				),
				array(
					$this->getFields(),
					$this->getJoinFields(),
					$this->_sql->getTableName(),
					null,
					null, 
					null,
					null,
					null,
					null
				),
				$this->_select
			)
		);
	}

	protected function getFields()
	{
		$fields = $this->_sql->getFields();
		return implode(', ', $fields);
	}

	protected function getJoinFields()
	{
		return null;
	}
}