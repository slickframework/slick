<?php

/**
 * Sql set data methods
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Database\Sql\Select;

use Slick\Database\Sql\Insert;

/**
 *  Sql set data methods
 *
 * @package   Slick\Database\Sql
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
trait SetDataMethods
{

    /**
     * @var array
     */
    protected $_fields = [];

    /**
     * @var array
     */
    protected $_parameters = [];

    /**
     * Sets the data for current SQL query
     *
     * @param array $data
     *
     * @return Insert
     */
    public function set(array $data)
    {
        foreach ($data as $field => $value) {
            $this->_fields[] = $field;
            $this->_parameters[":{$field}"] = $value;
        }
        return $this;
    }

    /**
     * Returns the parameters entered in set data
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Returns a list of field names separated by a comma
     *
     * @return string
     */
    public function getFieldList()
    {
        return implode(', ', $this->_fields);
    }

    /**
     * return the placeholder names separated by comma
     *
     * @return string
     */
    public function getPlaceholderList()
    {
        $names = array_keys($this->_parameters);
        return implode(', ', $names);
    }
}
