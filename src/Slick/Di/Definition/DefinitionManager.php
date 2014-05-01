<?php

/**
 * DefinitionManager
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
 * DefinitionManager
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DefinitionManager
{

    /**
     * List of definitions this manager has
     * @var DefinitionInterface[]
     */
    protected $_definitionSource = [];

    /**
     * Adds a definition to the list of definitions
     *
     * @param DefinitionInterface $definition
     *
     * @returns DefinitionManager
     */
    public function add(DefinitionInterface $definition)
    {
        $this->_definitionSource[$definition->getName()] = $definition;
        return $this;
    }

    /**
     * Checks if a definition with provided name exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->_definitionSource);
    }

    /**
     * Returns the definition stored with the provided name
     *
     * @param string $name
     *
     * @return null|\Slick\Di\DefinitionInterface
     */
    public function get($name)
    {
        $definition = null;
        if ($this->has($name)) {
            $definition = $this->_definitionSource[$name];
        }
        return $definition;
    }
} 