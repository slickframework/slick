<?php

/**
 * Definition
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Di\Definition\CallableDefinition;
use Slick\Di\Definition\Helper\FactoryDefinitionHelper;
use Slick\Di\Definition\Helper\ObjectDefinitionHelper;
use Slick\Di\Definition\ObjectDefinition\EntryReference;


/**
 * Definitions factory
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Definition
{

    /**
     * Creates a alias definition of the provided name
     *
     * @param string $definitionName The target definition name
     *
     * @return EntryReference
     */
    public static function link($definitionName)
    {
        return new EntryReference($definitionName);
    }

    /**
     * Creates a factory definition with a callable or anonymous function
     *
     * @param callable|\Closure $callable A callable or anonymous function
     * @param array             $arguments
     *
     * @return CallableDefinition
     */
    public static function factory(callable $callable, array $arguments = [] )
    {
        return new FactoryDefinitionHelper($callable, $arguments);
    }

    /**
     * Creates an object definition for the provided class name
     *
     * @param string $className
     *
     * @return ObjectDefinitionHelper
     */
    public static function object($className = null)
    {
        return new ObjectDefinitionHelper($className);
    }
} 