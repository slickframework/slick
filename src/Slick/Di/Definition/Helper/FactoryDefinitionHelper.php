<?php

/**
 * FactoryDefinitionHelper
 *
 * @package   Slick\Di\Definition\Helper
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition\Helper;

use Slick\Di\Definition\CallableDefinition;
use Slick\Di\Definition\DefinitionHelperInterface;

/**
 * Helps constructing a callable definition
 *
 * @package   Slick\Di\Definition\Helper
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class FactoryDefinitionHelper implements DefinitionHelperInterface
{

    /**
     * @var callable|\Closure
     */
    protected $_factory;

    /**
     * @var array
     */
    protected $_arguments;

    /**
     * Sets the factory and arguments to pass the callable definition
     *
     * @param callable $factory
     * @param array $arguments
     */
    public function __construct(callable $factory, array $arguments = [])
    {
        $this->_factory = $factory;
        $this->_arguments = $arguments;
    }

    /**
     * Returns a callable definition
     *
     * @param string $entryName Container entry name
     *
     * @return \Slick\Di\DefinitionInterface
     */
    public function getDefinition($entryName)
    {
        return new CallableDefinition($entryName, $this->_factory, $this->_arguments);
    }
}