<?php

/**
 * Abstract entity
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Entity;

use Slick\Common\Base;
use Slick\Di\Definition;
use Slick\Database\Adapter;
use Slick\Di\ContainerBuilder;
use Slick\Di\ContainerInterface;
use Slick\Orm\Entity;
use Zend\EventManager\EventManager;
use Slick\Di\ContainerAwareInterface;
use Slick\Configuration\Configuration;
use Slick\Database\Adapter\AdapterInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;

/**
 * Abstract entity
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $configName Configuration entry name to start a
 * database adapter
 * @property string $configFile Configuration file name
 * @property ContainerInterface $container Dependency injection container
 * @property AdapterInterface $adapter
 * @property string $primaryKey Primary key field name
 * @property bool $loadRelations Flag to enable/disable relation data loading
 * @property-read array $rawData Array or object used to create the entity
 *
 * @method string getPrimaryKey() Returns the primary key field name
 * @method Entity setPrimaryKey(string $pk) Sets the primary ky field name
 * @method array getRawData() Returns the array or object that was used to
 * create the entity
 */
abstract class AbstractEntity extends Base implements
    Adapter\AdapterAwareInterface,
    ContainerAwareInterface,
    EventManagerAwareInterface
{

    /**
     * @readwrite
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * @readwrite
     * @var string
     */
    protected $_configName = 'default';

    /**
     * @readwrite
     * @var string
     */
    protected $_configFile = 'database';

    /**
     * @readwrite
     * @var ContainerInterface
     */
    protected $_container;

    /**
     * @readwrite
     * @var string
     */
    protected $_primaryKey = 'id';

    /**
     * @readwrite
     * @var EventManagerInterface
     */
    protected $_events;

    /**
     * @readwrite
     * @var string
     */
    protected $_className;

    /**
     * @read
     * @var array
     */
    protected $_rawData;

    /**
     * @readwrite
     * @var bool Flag that enables/disables the relations loading
     */
    protected $_loadRelations = true;

    /**
     * Save all data in raw data before construction
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->setData($options);
    }

    // @codingStandardsIgnoreStart
    /**
     * Sets the value of a given property name.
     *
     * @param string $name The property name to set the value.
     * @param mixed  $value The value to assign to property.
     *
     * @return self The current object instance for
     * multiple (chain) method calls.
     */
    protected function _setter($name, $value)
    {
        // @codingStandardsIgnoreEnd
        $normalized = lcfirst($name);
        $property = "_{$normalized}";
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * Sets the adapter for this statement
     *
     * @param AdapterInterface $adapter
     * @return self
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Retrieves the current adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        if (is_null($this->_adapter)) {
            $key = "db_{$this->configName}";
            $this->setAdapter($this->getContainer()->get($key));
        }
        return $this->_adapter;
    }

    /**
     * Returns the internal dependency injector container
     *
     * @return ContainerInterface The dependency injector
     */
    public function getContainer()
    {
        if (is_null($this->_container)) {
            $def = [
                "db_{$this->configName}" => Definition::factory(
                        function() {
                            $args = Configuration::get($this->_configFile)
                                ->get($this->_configName);
                            $adapter = new Adapter($args);
                            return $adapter->initialize();
                        }
                    ),
                'sharedEventManager' => Definition::object(
                        'Zend\EventManager\SharedEventManager'
                    )
            ];
            $this->setContainer(ContainerBuilder::buildContainer($def));
        }
        return $this->_container;
    }

    /**
     * Sets the dependency injector container
     *
     * @param ContainerInterface $container The injector to set
     *
     * @return self A self instance for method chain calls
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->_container = $container;
        return $this;
    }

    /**
     * Sets event manager
     *
     * @param EventManagerInterface $events
     *
     * @return self
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_class($this)
        ));
        $events->setSharedManager(
            $this->getContainer()->get("sharedEventManager")
        );
        $this->_events = $events;
        return $this;
    }

    /**
     * Returns the event manager
     *
     * @return mixed|EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->_events) {
            $this->setEventManager(new EventManager());
        }
        return $this->_events;
    }

    /**
     * Returns the entity class name
     *
     * @return string
     */
    public function getClassName()
    {
        if (is_null($this->_className)) {
            $this->_className = get_called_class();
        }
        return $this->_className;
    }

    // @codingStandardsIgnoreStart
    /**
     * Proxies the call to a property that defines a relation
     *
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        // @codingStandardsIgnoreEnd

        //Check if its a relation
        $prop = "_{$name}";
        $manager = Manager::getInstance()->get($this);
        if (
            $this->_loadRelations &&
            is_null($this->$prop) &&
            $manager->isRelation($prop)
        ) {
            $this->$prop = $manager->getRelation($prop)->load($this);
        }

        // Not a relation, back to normal behavior
        return parent::__get($name);
    }

    /**
     * Sets model data
     *
     * @param array $data
     * @return self
     */
    public function setData($data)
    {
        $this->_rawData = $data;
        $this->_createObject($data);
        return $this;
    }
}
