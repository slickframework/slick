<?php

/**
 * Abstract column
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Ddl\Column;

/**
 * Abstract column - Basic column implementation
 *
 * @package   Slick\Database\Sql\Ddl\Column
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractColumn implements ColumnInterface
{

    /**
     * @var string
     */
    protected $_name;

    /**
     * @param string $name Column name
     *
     * @param array $options
     * Options can be (depending on the column type):
     *  - length
     *  - size
     *  - nullable
     *  - default
     *  - autoIncrement
     *  - precision
     *  - scale
     *  - digits
     *  - decimal
     */
    public function __construct($name, $options = [])
    {
        $this->_name = $name;
        foreach ($options as $key => $value) {
            $prop = "_{$key}";
            if (isset($this->$prop)) {
                $this->$prop = $value;
            }
        }
    }

    /**
     * Returns column name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
}
