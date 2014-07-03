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

namespace Slick\Database\Sql;

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
    protected $fields = [];

    /**
     * @var array
     */
    protected $dataParameters = [];

    /**
     * Sets the data for current SQL query
     *
     * @param array $data
     *
     * @return Insert|Update
     */
    public function set(array $data)
    {
        foreach ($data as $field => $value) {
            $this->fields[] = $field;
            $this->dataParameters[":{$field}"] = $value;
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
        return $this->dataParameters;
    }

    /**
     * Returns a list of field names separated by a comma
     *
     * @return string
     */
    public function getFieldList()
    {
        return implode(', ', $this->fields);
    }

    /**
     * return the placeholder names separated by comma
     *
     * @return string
     */
    public function getPlaceholderList()
    {
        $names = array_keys($this->dataParameters);
        return implode(', ', $names);
    }

    /**
     * Returns the field names
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
