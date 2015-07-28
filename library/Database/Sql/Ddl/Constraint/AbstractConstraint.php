<?php

/**
 * This file is part of slick/database package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Abstract able constraint
 *
 * @package Slick\Database\Sql\Ddl\Constraint
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractConstraint implements ConstraintInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * Creates a new constraint
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, $options = [])
    {
        $this->name = $name;
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Returns constraint name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}