<?php

/**
 * Submit
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Element;


use Slick\Form\Template\AbstractTemplate,
    Slick\Form\Template\SubmitInput,
    Slick\Form\Element;

class Submit extends Element
{

    /**
     * @readwrite
     * @var bool
     */
    protected $_glyph = true;

    /**
     * @readwrite
     * @var string
     */
    protected $_glyphIcon = 'glyphicon-save';

    /**
     * Returns the glyph use state
     *
     * @return bool
     */
    public function hasGlyph()
    {
        return $this->_glyph;
    }

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [
        'type' => 'submit',
        'class' => 'btn btn-primary'
    ];

    /**
     * lazy loads a default template for this element
     *
     * @return AbstractTemplate
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $this->_template = new SubmitInput();
        }
        return $this->_template;
    }

    public function getHtmlAttributes()
    {
        $result = parent::getHtmlAttributes();
        return trim(str_replace('form-control', '', $result));
    }
} 