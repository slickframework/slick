<?php

/**
 * Select
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Element;

use Slick\Form\Element as BasicElement;
use Slick\Form\Template\AbstractTemplate;
use Slick\Form\Template\SelectInput;

/**
 * Select
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property array $options    Select box options
 * @property array $attributes HTML attributes
 */
class Select extends BasicElement
{

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [];

    /**
     * @readwrite
     * @var array
     */
    protected $_options = [];

    /**
     * lazy loads a default template for this element
     *
     * @return AbstractTemplate
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $this->_template = new SelectInput();
        }
        return $this->_template;
    }

} 