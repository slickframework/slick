<?php

/**
 * Entity assets manager
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Orm\Entity;

use Slick\Common\Inspector;
use Slick\Orm\Entity;
use Slick\Common\BaseSingleton;
use Slick\Common\SingletonInterface;

/**
 * Entity assets manager
 *
 * @package   Slick\Orm\Entity
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Manager extends BaseSingleton
{

    /**
     * @var self
     */
    private static $_instance;

    /**
     * @read
     * @var Descriptor[]
     */
    protected $_entities = [];

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
            Inspector::addAnnotationClass('column', 'Slick\Orm\Annotation\Column');
            Inspector::addAnnotationClass(
                strtolower('HasMany'),
                'Slick\Orm\Annotation\HasMany'
            );
        }
        return static::$_instance;
    }

    /**
     * Returns the descriptor for the provided entity object
     *
     * @param Entity $entity
     *
     * @return Descriptor
     */
    public function get(Entity $entity)
    {
        $name = get_class($entity);
        if (!isset($this->_entities[$name])) {
            $this->_entities[$name] = new Descriptor(['entity' => $entity]);
        }
        return $this->_entities[$name];
    }
}
