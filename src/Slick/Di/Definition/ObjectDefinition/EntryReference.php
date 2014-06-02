<?php

/**
 * EntryReference
 *
 * @package   Slick\Di\Definition\ObjectDefinition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition\ObjectDefinition;

/**
 * Used in parameters, it tells resolver to look in the container for a value
 *
 * @package   Slick\Di\Definition\ObjectDefinition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class EntryReference
{

    /**
     * Entry name
     * @var string
     */
    private $_name;

    /**
     * @param string $entryName Entry name
     */
    public function __construct($entryName)
    {
        $this->_name = $entryName;
    }

    /**
     * @return string Entry name
     */
    public function getName()
    {
        return $this->_name;
    }


} 