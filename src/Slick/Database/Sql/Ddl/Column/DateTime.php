<?php

/**
 * DateTime column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * DateTime column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class DateTime extends AbstractColumn
{

    /**
     * @var bool
     */
    protected $_nullable = false;

    /**
     * Sets new column with a given name
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }

    /**
     * Set the ISNULL flag for this column
     *
     * @param boolean $nullable
     *
     * @return Integer
     */
    public function setNullable($nullable)
    {
        $this->_nullable = $nullable;
    }

    /**
     * Gets the ISNULL flag for this column
     *
     * @return boolean
     */
    public function getNullable()
    {
        return $this->_nullable;
    }
}
