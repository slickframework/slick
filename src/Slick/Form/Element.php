<?php

/**
 * Element
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

/**
 * Element
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class Element extends AbstractElement implements ElementInterface
{
    /**
     * @readwrite
     * @var array
     */
    protected $_attributes = [
        'type' => 'text'
    ];
}