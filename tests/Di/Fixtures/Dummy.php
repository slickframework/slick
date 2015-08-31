<?php

/**
 * This file is part of slick/di package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Di\Fixtures;


class Dummy
{
    public $value;

    private $name;

    public function __construct($value = 'test')
    {
        $this->setValue($value);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}