<?php

/**
 * MVC Model
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Orm\Entity;
use Slick\Database\Sql;
use Slick\Mvc\Model\Manager;
use Slick\Orm\Entity\Manager as EntityManager;

/**
 * MVC Model
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property-read Model\Descriptor $descriptor The descriptor object
 */
class Model extends Entity
{

    /**
     * Returns the primary key value
     *
     * @return integer
     */
    public function getKey()
    {
        $prmKey = $this->primaryKey;
        return $this->$prmKey;
    }

    /**
     * Returns the display field name
     *
     * The display field is used to print out the model instance name
     * when you request to print a model.
     *
     * For example:
     * model as the id, name, address fields, if you print out model with
     * echo $model, it will use the name field to print it or other field
     * if you define $_displayField property.
     *
     * @return string
     */
    public function getDisplayField()
    {
        return $this->getDescriptor()->getDisplayField();
    }

    /**
     * Returns the model descriptor for this model class
     *
     * @return Model\Descriptor
     */
    public function getDescriptor()
    {
        $entityDescriptor = EntityManager::getInstance()->get($this);
        return Manager::getInstance()->get($entityDescriptor);
    }

    /**
     * Prints out this module text representation
     *
     * @return string
     */
    public function __toString()
    {
        $displayField = $this->getDisplayField();
        return (String) $this->$displayField;
    }

    /**
     * Retrieves an array with primary keys and display fields
     *
     * This is used mainly for selected options
     *
     * @return array
     */
    public static function getList()
    {
        /** @var Model $model */
        $model = new static();
        $fields = [
            trim($model->getPrimaryKey(), '_'),
            trim($model->getDisplayField(), '_')
        ];
        $rows = Sql::createSql($model->getAdapter())
            ->select($model->getTableName(), $fields)
            ->limit(null)
            ->all();
        $list = [];
        list($id, $value) = $fields;
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $list[$row[$id]] = $row[$value];
            }
        }
        return $list;
    }
}
