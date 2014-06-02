<?php

/**
 * Definition helper interface
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di\Definition;

/**
 * Defines a definition helper used in definition factory
 *
 * @package   Slick\Di\Definition
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DefinitionHelperInterface
{
    /**
     * @param string $entryName Container entry name
     * @return \Slick\Di\DefinitionInterface
     */
    public function getDefinition($entryName);
} 