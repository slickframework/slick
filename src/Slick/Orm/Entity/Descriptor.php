<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 8/26/14
 * Time: 7:05 PM
 */

namespace Slick\Orm\Entity;


use Slick\Common\Base;
use Slick\Common\Inspector;
use Slick\Orm\Annotation\Column;
use Slick\Orm\Entity;

class Descriptor extends Base
{
    /**
     * @read
     * @var Inspector
     */
    protected $_inspector;

    /**
     * @read
     * @var Column[]
     */
    protected $_columns = [];

    /**
     * @readwrite
     * @var Entity
     */
    protected $_entity;

    /**
     * Returns the list of entity columns
     *
     * @return Column[]
     */
    public function getColumns()
    {
        if (empty($this->_columns)) {
            $properties = $this->getInspector()->getClassProperties();
            foreach ($properties as $property) {
                $annotations = $this->getInspector()->getPropertyAnnotations($property);
                $noos = $annotations->getArrayCopy();
                if ($annotations->hasAnnotation('column')) {
                    $this->_columns[$property] = $annotations->getAnnotation('column');
                }
            }
        }
        return $this->_columns;
    }

    /**
     * Returns the inspector class for current entity
     *
     * @return Inspector
     */
    public function getInspector()
    {
        if (is_null($this->_inspector)) {
            $this->_inspector = new Inspector($this->_entity);
        }
        return $this->_inspector;
    }
} 