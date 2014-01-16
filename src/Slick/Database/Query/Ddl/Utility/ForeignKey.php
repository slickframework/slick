<?php

/**
 * ForeignKey
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl\Utility;

use Slick\Common\Base;

/**
 * ForeignKey
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ForeignKey extends Base implements TableElementInterface
{

    /**#@+
     * @const int The on delete and on update actions
     */
    const NO_ACTION = 0;
    const RESTRICT  = 1;
    const SET_NULL  = 2;
    const CASCADE   = 3;
    /**#@-*/

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var string The referenced table name
     */
    protected $_referencedTable;

    /**
     * @readwrite
     * @var array A key/value pair for field/reference values
     */
    protected $_indexColumns = array();

    /**
     * @readwrite
     * @var integer The action upon update
     */
    protected $_onUpdate = self::NO_ACTION;

    /**
     * @readwrite
     * @var integer The action upon delete
     */
    protected $_onDelete = self::NO_ACTION;


    /**
     * Adds a ner indec column reference to this foreignKey
     * 
     * @param string $name      The table field name
     * @param string $reference The referenced table field name
     *
     * @return \Slick\Database\Query\Ddl\Utility\ForeignKey A sel instance for
     *  method call chains.
     */
    public function addIndexColumn($name, $reference)
    {
        $this->_indexColumns[$name] = $reference;
        return $this;
    }
}