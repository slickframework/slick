<?php

/**
 * Hidden
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
use Slick\Form\Template\HiddenInput;

/**
 * Hidden
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Hidden extends BasicElement
{

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [
        'type' => 'hidden'
    ];

    /**
     * lazy loads a default template for this element
     *
     * @return AbstractTemplate
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $this->_template = new HiddenInput();
        }
        return $this->_template;
    }
}