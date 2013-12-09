<?php

/**
 * Alter
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Sql\Dialect\Standard;

/**
 * Alter
 *
 * @package   Slick\Database\Query\Sql\Dialect\Standard
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Alter extends Create
{

	/**
     * @read
     * @var string The Create Table template SQL
     */
    protected $_template = <<<EOS
ALTER TABLE `<tableName>`
<definition>
<options>
EOS;

	/**
     * @read
     * @var string To use in column definition perfix
     */
    protected $_definitionPrefix = 'ADD COLUMN ';

    /**
     * Returns all columns, index and constraints definitions
     * 
     * @return string SQL for columns, indexes and constraints
     */
    public function getDefinitions()
    {
        $sql = '';
        $sql .= $this->_getColumns();
        $sql .= $this->_getIndexes();
        $sql .= $this->_getConstraints();
        return $sql;
    }

    /**
     * Generate Index definitions for create table statement
     * @return string
     */
    protected function _getIndexes($prefix = '')
    {
    	return $this->_tab . trim(ltrim(parent::_getIndexes('ADD '), ','));
    }
}