<?php

/**
 * Value definition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition;

use Slick\Di\DefinitionInterface;

/**
 * Value definition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ValueDefinition implements DefinitionInterface
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var mixed
     */
    protected $_value;

    /**
     *  Definition of a value for dependency injection.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __construct($name, $value)
    {
        $this->_name = $name;
        $this->_value = $value;
    }

    /**
     * Returns the name of the entry in the container
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns the scope of the entry
     *
     * @return Scope
     */
    public function getScope()
    {
        return Scope::SINGLETON();
    }

    /**
     * Returns current definition value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->_value;
    }
}