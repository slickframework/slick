<?php

/**
 * MVC Model descriptor
 *
 * @package   Slick\Mvc\Model
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Model;

use Slick\Common\Base;
use Slick\Orm\Entity\Descriptor as SlickOrmDescriptor;

/**
 * MVC Model descriptor
 *
 * @package   Slick\Mvc\Model
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property SlickOrmDescriptor $descriptor Entity descriptor object
 *
 * @method SlickOrmDescriptor getDescriptor() Returns the Entity descriptor
 */
class Descriptor extends Base
{
    /**
     * @readwrite
     * @var string
     */
    protected $_displayField;

    /**
     * @readwrite
     * @var SlickOrmDescriptor
     */
    protected $_descriptor;

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
        if (is_null($this->_displayField)) {
            $properties = array_keys($this->getDescriptor()->getColumns());
            foreach ($properties as $property) {
                $name = trim($property, '_');
                $pmk = $this->getDescriptor()->getEntity()->primaryKey;
                if ($name == $pmk) {
                    continue;
                }

                $annotations = $this->getDescriptor()->getInspector()
                    ->getPropertyAnnotations($property);

                if ($annotations->hasAnnotation('@display')) {
                    $this->_displayField = $name;
                    break;
                }
                $this->_displayField = $name;
            }
        }
        return $this->_displayField;

    }
}
