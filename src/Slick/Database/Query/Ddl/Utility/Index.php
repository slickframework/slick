<?php

/**
 * Index
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Database\Query\Ddl\Utility;

use Slick\Common\Base,
    Slick\Database\Exception;

/**
 * Index
 *
 * @package   Slick\Database\Query\Ddl\Utility
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Index extends Base implements TableElementInterface
{

    /**#@+
     * @const int The index types
     */
    const PRIMARY_KEY = 0;
    const INDEX       = 1;
    const FULLTEXT    = 2;
    const UNIQUE      = 3;
    /**#@-*/

    /**#@+
     * @const int The index storage type
     */
    const STORAGE_NONE  = 0;
    const STORAGE_BTREE = 1;
    const STORAGE_RTREE = 2;
    const STORAGE_HASH  = 3;
    /**#@-*/

    /**
     * @readwrite
     * @var string
     */
    protected $_name;

    /**
     * @readwrite
     * @var integer The index type
     */
    protected $_type = self::INDEX;

    /**
     * @readwrite
     * @var array A list of table fields to use in this index
     */
    protected $_indexColumns = array();

    /**
     * @readwrite
     * @var integer The type for this index storage
     */
    protected $_storageType = self::STORAGE_NONE;

    /**
     * Sets the type for this index.
     *
     * You must use the constants that this class defines. This way its
     * possible to decouple the index definition form its database language
     * syntax.
     * 
     * @param integer $type The type as defined by this class
     *
     * @return \Slick\Database\Query\Ddl\Utility\Index A self instance for
     *  method call chains
     *
     * @throws \Slick\Database\Exception\InvalidArgumentException If the
     *  provided type is not one of this class type constants.
     */
    public function setType($type)
    {
        if ((!is_numeric($type)) || $type < 0 || $type > 3) {
            throw new Exception\InvalidArgumentException(
                "Trying to set an unknown index type"
            );
        }

        $this->_type = $type;
        return $this;
    }

    /**
     * Sets the storage type for this index
     *
     * You must use the constants that this class defines. This way its
     * possible to decouple the index definition form its database language
     * syntax.
     * 
     * @param integer $type The type as defined by this class
     *
     * @return \Slick\Database\Query\Ddl\Utility\Index A self instance for
     *  method call chains
     * 
     * @throws \Slick\Database\Exception\InvalidArgumentException If the
     *  provided type is not one of this class type constants.
     */
    public function setStorageType($type)
    {
        if ((!is_numeric($type)) || $type < 0 || $type > 3) {
            throw new Exception\InvalidArgumentException(
                "Trying to set an unknown index storage type"
            );
        }
        $this->_storageType = $type;
        return $this;
    }

    /**
     * Adds a column name to the index columns
     * 
     * @param string $name The column of field name.
     *
     * @return \Slick\Database\Query\Ddl\Utility\Index A self instance for
     *  method call chains
     */
    public function addColumn($name)
    {
        $this->_indexColumns[] = $name;
        return $this;
    }
}