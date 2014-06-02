<?php

/**
 * AliasDefinition
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
 * This definition is a link to another definition
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AliasDefinition implements DefinitionInterface
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var string
     */
    protected $_targetEntryName;

    /**
     * Creates a new Alias definition
     *
     * @param string $name
     * @param string $targetEntryName
     */
    public function __construct($name, $targetEntryName)
    {
        $this->_name = $name;
        $this->_targetEntryName = $targetEntryName;
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
        return Scope::PROTOTYPE();
    }

    /**
     * Return the target entry name
     *
     * @return string
     */
    public function getTargetEntryName()
    {
        return $this->_targetEntryName;
    }


}