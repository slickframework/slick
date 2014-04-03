<?php

/**
 * TemplateInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form;

use Slick\Template\EngineInterface;

/**
 * TemplateInterface
 *
 * @package   Slick\Form
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
interface TemplateInterface
{

    /**
     * Returns the element to decorate
     *
     * @return ElementInterface
     */
    public function getElement();

    /**
     * Sets the element to decorate
     *
     * @param ElementInterface $element
     *
     * @return TemplateInterface
     */
    public function setElement(ElementInterface $element);

    /**
     * Renders out the HTML of current element
     *
     * @return string HTML output
     */
    public function render();

    /**
     * Sets current template
     *
     * @param EngineInterface $template
     *
     * @return TemplateInterface
     */
    public function setTemplate(EngineInterface $template);

    /**
     * Returns current template interface
     *
     * @return EngineInterface
     */
    public function getTemplate();
} 