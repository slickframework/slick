<?php

/**
 * Form
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc\Scaffold;

use Slick\Common\Inspector;
use Slick\Form\Element\Text;
use Slick\Form\Element\Area;
use Slick\Form\Element\Hidden;
use Slick\Form\Element\Submit;
use Slick\Mvc\Model\Descriptor;
use Slick\Form\Element\DateTime;
use Slick\Form\Element\Checkbox;
use Slick\Orm\Annotation\Column;
use Slick\Orm\RelationInterface;
use Slick\Form\Form as SlickFrom;

/**
 * Form
 *
 * @package   Slick\Mvc\Router
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Form extends SlickFrom
{

    /**
     * @read
     * @var Descriptor
     */
    protected $_descriptor;

    /**
     * @var Column[]
     */
    private $_columns = [];

    /**
     * @var RelationInterface[]
     */
    private $_relations = [];

    /**
     * @var array
     */
    private $_properties = [];

    /**
     * @var Inspector
     */
    private $_inspector;

    /**
     * Add elements to the form based on the model notations
     *
     * @param string $name
     * @param Descriptor $descriptor
     */
    public function __construct($name, Descriptor $descriptor)
    {
        $this->_descriptor = $descriptor;
        $this->_columns = $this->_descriptor->getColumns();
        $this->_relations = $this->_descriptor->getRelations();
        $inspector = new Inspector($this->_descriptor->getDescriptor()->getEntity());
        $this->_properties = $inspector->getClassProperties();
        parent::__construct($name);
    }

    /**
     * Callback for form setup
     */
    protected function _setup()
    {
        foreach ($this->_properties as $property) {
            $element = $this->_createElement($property);
            if ($element) {
                $this->add($element);
            }
        }
        $this->add(
            new Submit(
                ['value' => 'Save']
            )
        );
    }

    protected function _createElement($property)
    {
        if (isset($this->_columns[$property])) {
            return $this->_createFromColumn(
                $property,
                $this->_columns[$property]
            );
        }
        return false;
    }

    protected function _createFromColumn($property, Column $column)
    {
        $name = trim($property, '_');
        $options = [
            'name' => $name,
            'label' => ucfirst($name)
        ];
        $element = new Text($options);
        if ($column->getParameter('primaryKey')) {
            $element = new Hidden($options);
        }
        if ($column->getParameter('size') == 'big') {
            $element = new Area($options);
        }
        if ($column->getParameter('type') == 'boolean') {
            $element = new Checkbox($options);
        }
        if ($column->getParameter('type') == 'datetime') {
            $element = new DateTime($options);
        }
        return $element;
    }
}
