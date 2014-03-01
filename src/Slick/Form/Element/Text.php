<?php

/**
 * Text
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Element;

use Slick\Form\Element as BasicElement;

/**
 * Text
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Text extends BasicElement
{

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [
        'type' => 'text'
    ];
}