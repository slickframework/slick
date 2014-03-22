<?php

/**
 * Checkbox
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Element;

use Slick\Form\Template\CheckboxInput,
    Slick\Form\Element as BasicElement,
    Slick\Form\Template\AbstractTemplate;

/**
 * Checkbox
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Checkbox extends BasicElement
{

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [];

    /**
     * lazy loads a default template for this element
     *
     * @return AbstractTemplate
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $this->_template = new CheckboxInput();
        }
        return $this->_template;

    }

    /**
     * Overrides the default method to remove the class form-control
     *
     * @return string|void
     */
    public function getHtmlAttributes()
    {
        if ( $this->getValue()) {
            $this->_attributes['checked'] = 'checked';
        }
        $text = parent::getHtmlAttributes();
        $text = str_replace('form-control', '', $text);
        return $text;
    }
} 