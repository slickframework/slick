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
use Slick\Di\DependencyInjector;
use Slick\Di\DiAwareInterface;
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
 *
 * @property RelationManager relationsManager
 */
class AbstractEntity extends Base implements DiAwareInterface
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
     * @var RelationManager
     */
    protected $_relationsManager;

    /**
     * @readwrite
     * @var mixed
     */
    protected $_raw;

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
        $columns = $this->getColumns();
        if (!$columns->hasPrimaryKey()) {
            throw new Exception\PrimaryKeyException(
                "Entity {$this->alias} does not have a column as primary key."
            );
        }

        $this->_hydratate($options);

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
                $propertyMeta = $inspector->getPropertyMeta($property);
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
            $cfg = Configuration::get('database')
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
     * Returns dependency injector container
     *
     * @return DependencyInjector
     */
    public function getDi()
    {
        if (is_null($this->_dependencyInjector)) {
            $this->_dependencyInjector = DependencyInjector::getDefault();
        }
        return $this->_dependencyInjector;
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
    protected function _hydratate($options)
    {
        $columns = $this->getColumns();
        if (is_array($options) || is_object($options)) {
            foreach ($options as $key => $value) {
                if ($columns->hasColumn($key)) {
                    $prop = is_array($value) ? reset($value) : $value;
                    $key = ucfirst($key);
                    $method = "set{$key}";
                    $this->$method($prop);
                }
            }
            $this->_raw = $options;
        }
    }
} 