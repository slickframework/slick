<?php

/**
 * DefinitionInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Di;

use Slick\Di\Definition\Scope;

/**
 * DefinitionInterface
 *
 * @package   Slick\Di
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface DefinitionInterface
{

    /**
     * Returns the name of the entry in the container
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the scope of the entry
     *
     * @return Scope
     */
    public function getScope();
} 