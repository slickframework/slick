<?php

/**
 * Select multiple
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Form\Element;
use Slick\Form\Template\AbstractTemplate;

/**
 * Select multiple
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class SelectMultiple extends Select
{

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [
        'multiple'
    ];

    /**
     * lazy loads a default template for this element
     *
     * @return AbstractTemplate
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            $this->_template = new \Slick\Form\Template\SelectMultiple();
        }
        return $this->_template;
    }
}
