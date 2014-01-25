<?php
/**
 * AbstractEntity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Orm;

use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Database\Connector\AbstractConnector;
use Slick\Orm\Entity\Column;
use Slick\Utility\Text;
use Slick\Orm\Entity\ColumnList;

/**
 * AbstractEntity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractEntity extends Base
{
    /**
     * @readwrite
     * @var AbstractConnector
     */
    protected $_connector;

    /**
     * @readwrite
     * @var string
     */
    protected $_table;

    /**
     * @readwrite
     * @var string Primary key field name
     */
    protected $_primaryKey = 'id';

    /**
     * @readwrite
     * @var string The alias/name for this entity
     */
    protected $_alias;

    /**
     * The list of table columns defined by this entity.
     * @var ColumnList
     */
    protected $_columns;

    /**
     * Returns the model alias use in select queries.
     *
     * @return string The model alias.
     */
    public function getAlias()
    {
        if (empty($this->_alias)) {
            $parts = explode('\\', get_class($this));
            $name = end($parts);
            $this->_alias = $name;
        }
        return $this->_alias;
    }

    /**
     * Returns the model table name
     *
     * If no set it will inflate form the model name.
     * Example: User -> users, Person -> people, etc...
     *
     * @return string The table name that this model is linked with.
     */
    public function getTable()
    {
        if (empty($this->_table)) {
            $parts = explode('\\', get_class($this));
            $name = end($parts);
            $this->_table = Text::plural(strtolower($name));
        }
        return $this->_table;
    }

    /**
     * Returns the list of columns of this entity
     *
     * @return ColumnList
     */
    public function getColumns()
    {
        if (is_null($this->_columns)) {
            $inspector = new Inspector($this);
            $properties = $inspector->getClassProperties();
            $this->_columns = new ColumnList();

            foreach ($properties as $property)  {
                $propertyMeta = $inspector->getPropertyMeta($property);
                $column = Column::parse($propertyMeta, $property);
                if ($column) {
                    $this->_columns->append($column);
                }
            }
        }
        return $this->_columns;
    }
} 