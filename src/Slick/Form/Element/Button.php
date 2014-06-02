<?php

/**
 * Button
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Element;

/**
 * Button
 *
 * @package   Slick\Form\Element
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Button extends Submit
{

    /**
     * @readwrite
     * @var bool
     */
    protected $_glyph = false;

    /**
     * @readwrite
     * @var array HTML attributes
     */
    protected $_attributes = [
        'type' => 'button',
        'class' => 'btn btn-default'
    ];
} 