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
use Slick\Database\Database;
use Slick\Di\ContainerAwareInterface;
use Slick\Di\ContainerAwareTrait;
use Slick\Orm\Relation\RelationManager;
use Slick\Utility\Text;
use Slick\Orm\Entity\Column,
    Slick\Orm\Entity\ColumnList,
    Slick\Orm\Exception;
use Slick\Database\Connector\AbstractConnector,
    Slick\Database\Connector\ConnectorInterface,
    Slick\Database\Query\Query;
use Slick\Configuration\Configuration;

/**
 * AbstractEntity
 *
 * @package   Slick\Orm
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractEntity extends Base implements ContainerAwareInterface
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
     * @var ColumnList[]
     */
    protected $_columns = [];

    /**
     * @readwrite
     * @var string Data source configuration name
     */
    protected $_dataSourceName = 'default';

    /**
     * @readwrite
     * @var string The configuration file name
     */
    protected $_configFile = 'database';

    /**
     * @readwrite
     * @var RelationManager
     */
    protected $_relationsManager;

    /**
     * @readwrite
     * @var mixed
     */
    protected $_raw;

    /**
     * @read
     * @var mixed
     */
    protected $_remainingData;

    /**
     * Container aware interface implementation
     */
    use ContainerAwareTrait;

    /**
     * Overrides Base constructor to handle property populating process
     * from throwing undefined property exceptions.
     *
     * @param array|Object $options
     * @throws Exception\PrimaryKeyException
     */
    public function __construct($options = array())
    {
        parent::__construct();

        Inspector::addAnnotationClass('column', 'Slick\Orm\Entity\ColumnAnnotation');
        Inspector::addAnnotationClass('hasAndBelongsToMany', 'Slick\Orm\Relation\RelationAnnotation');
        Inspector::addAnnotationClass('belongsTo', 'Slick\Orm\Relation\RelationAnnotation');
        Inspector::addAnnotationClass('hasMany', 'Slick\Orm\Relation\RelationAnnotation');
        Inspector::addAnnotationClass('hasOne', 'Slick\Orm\Relation\RelationAnnotation');

        $columns = $this->getColumns();
        if (!$columns->hasPrimaryKey()) {
            throw new Exception\PrimaryKeyException(
                "Entity {$this->alias} does not have a column as primary key."
            );
        }

        $this->_raw = $options;
        $this->_hydrate($options);


    }

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
        $name = $this->getAlias();
        if (!isset($this->_columns[$name])) {
            $inspector = new Inspector($this);
            $properties = $inspector->getClassProperties();
            $this->_columns[$name] = new ColumnList();

            foreach ($properties as $property)  {
                $propertyMeta = $inspector->getPropertyAnnotations($property);
                $column = Column::parse($propertyMeta, $property);
                if ($column) {
                    $this->_columns[$name]->append($column);
                }

                $this->getRelationsManager()->check($propertyMeta, $property, $this);
            }
        }
        return $this->_columns[$name];
    }

    /**
     * Returns a query object for custom queries
     *
     * @param null $sql A custom SQL query
     *
     * @return Query A query interface for custom queries
     */
    public function query($sql = null)
    {
        return $this->getConnector()->query($sql);
    }

    /**
     * Returns the database connector (adapter)
     *
     * @return ConnectorInterface
     */
    public function getConnector()
    {
        if (is_null($this->_connector)) {
            $cfg = Configuration::get($this->_configFile)
                ->get($this->_dataSourceName, ['type' => 'SQLite']);
            $type = $cfg['type'];
            unset($cfg['type']);
            $connector = new Database(
                [
                    'type' => $type,
                    'options' => $cfg
                ]
            );
            $this->_connector = $connector->initialize()->connect();
        }
        return $this->_connector;
    }

    /**
     * Returns entity relations manager
     *
     * @return RelationManager
     */
    public function getRelationsManager()
    {
        if (is_null($this->_relationsManager)) {
            $this->_relationsManager = new RelationManager();
        }
        return $this->_relationsManager;
    }

    /**
     * @param string $name
     * @return mixed
     */
    // @codingStandardsIgnoreStart
    public function __get($name)
    {
        // @codingStandardsIgnoreEnd

        //Check if its a relation
        $prop = "_{$name}";
        $rlMan = $this->getRelationsManager();
        if ($rlMan->isARelation($prop)) {
            if (is_null($this->$prop)) {
                $this->$prop = $rlMan->getRelation($prop)->load($this);
            }
            return $this->$prop;
        }

        // Not a relation, back to normal behavior
        return parent::__get($name);
    }

    /**
     * Checks property existence before assigning values to it.
     *
     * This method changes the behavior of Base::_setter() that throws an
     * exception if the property does not exists by checking if there is
     * a defined property with @column notation with the same name as
     * the data key you are trying to assign.
     *
     * @param $options
     */
    protected function _hydrate($options)
    {
        $columns = $this->getColumns();
        if (is_array($options) || is_object($options)) {
            $optionsCopy = $options;
            foreach ($optionsCopy as $key => $value) {
                if ($columns->hasColumn($key)) {
                    $prop = $value;
                    if (is_array($value)) {
                        $val = array_shift($options[$key]);
                        $prop = $val;
                    }

                    $key = ucfirst($key);
                    $method = "set{$key}";
                    $this->$method($prop);
                    unset($options[$key]);
                }
            }
        }
        $this->_remainingData = $options;
    }
}