<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Di\Fixtures;

/**
 * Class Object
 *
 * @package Di\Fixtures
 */
class Object
{

    private $name;

    public function __construct($name = null)
    {
        $this->name = $name;
    }
}