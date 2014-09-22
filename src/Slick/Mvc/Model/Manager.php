<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 9/16/14
 * Time: 1:10 PM
 */

namespace Slick\Mvc\Model;


use Slick\Orm\Entity\Descriptor as SlickOrmDescriptor;

class Manager
{

    /**
     * @var self
     */
    private static $_instance;

    /**
     * @read
     * @var Descriptor[]
     */
    protected $_models = [];

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar SingletonInterface $instance The *Singleton* instances
     *  of this class.
     *
     * @param array $options The list of property values of this instance.
     *
     * @return self The *Singleton* instance.
     */
    public static function getInstance($options = array())
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static($options);
        }
        return static::$_instance;
    }

    /**
     * Return a model descriptor for given entity descriptor
     *
     * @param SlickOrmDescriptor $descriptor
     *
     * @return \Slick\Mvc\Model\Descriptor
     */
    public function get(SlickOrmDescriptor $descriptor)
    {
        $name = $descriptor->getEntity()->getClassName();
        if (!isset($this->_models[$name])) {
            $this->_models[$name] = new Descriptor(
                ['descriptor' => $descriptor]
            );
        }
        return $this->_models[$name];
    }
}
