<?php

/**
 * Form
 *
 * @package   Slick\Mvc\Scaffold
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Scaffold;

use Slick\Form\Element;
use Slick\Form\Form as SlickFrom,
    Slick\Mvc\Model;

/**
 * Form
 *
 * @package   Slick\Mvc\Scaffold
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Form extends SlickFrom
{

    /**
     * @readwrite
     * @var Model
     */
    protected $_model;

    /**
     * Add elements to the form based on the model notations
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name, $options = array())
    {
        parent::__construct($name, $options);
        foreach($this->getModel()->getPropertyList() as $name => $property) {
            $name = trim($name, '_');
            $this->add(new Element\Text(['name' => $name]));
        }
    }

    /**
     * Lazy loads the model object
     *
     * @return Model
     */
    public function getModel()
    {
        if (is_string($this->_model)) {
            $class = $this->_model;
            $this->_model = new $class;
        }
        return $this->_model;
    }

} 