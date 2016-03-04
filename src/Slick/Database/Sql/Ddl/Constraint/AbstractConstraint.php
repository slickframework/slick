<?php

/**
 * Abstract able constraint
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Constraint;

/**
 * Abstract able constraint
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    protected $_name;

    /**
     * Creates a new constraint
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, $options = [])
    {
        $this->_name = $name;
        foreach ($options as $key => $value) {
            $prop = "_{$key}";
            if (property_exists($this, $prop)) {
                $this->$prop = $value;
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
        return $this->_name;
    }
}