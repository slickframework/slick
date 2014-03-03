<?php

/**
 * AbstractTemplate
 *
 * @package   Slick\Form\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Form\Template;

use Slick\Common\Base,
    Slick\Form\Element,
    Slick\Template\Engine\Twig,
    Slick\Form\TemplateInterface,
    Slick\Form\ElementInterface,
    Slick\Template\EngineInterface,
    Slick\Template\Template;

/**
 * AbstractTemplate
 *
 * @package   Slick\Form\Template
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Twig $template
 * @property Element $element
 */
abstract class AbstractTemplate extends Base implements TemplateInterface
{

    /**
     * @readwrite
     * @var Twig
     */
    protected $_template;

    /**
     * @readwrite
     * @var Element
     */
    protected $_element;

    /**
     * @readwrite
     * @var string
     */
    protected $_templateFile = 'default-input.html.twig';

    /**
     * Returns the element to decorate
     *
     * @return ElementInterface
     */
    public function getElement()
    {
        return $this->_element;
    }

    /**
     * Sets the element to decorate
     *
     * @param ElementInterface $element
     *
     * @return AbstractTemplate
     */
    public function setElement(ElementInterface $element)
    {
        $this->_element = $element;
        return $this;
    }

    /**
     * Sets current template
     *
     * @param EngineInterface $template
     *
     * @return AbstractTemplate
     */
    public function setTemplate(EngineInterface $template)
    {
        $this->_template = $template;
        return $this;
    }

    /**
     * Returns current template interface
     *
     * @return EngineInterface
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            Template::appendPath(dirname(__FILE__).'/Views');
            $temp = new Template();
            $this->_template = $temp->initialize();
        }
        return $this->_template;
    }

    /**
     * Renders out the HTML of current element
     *
     * @return string HTML output
     */
    public function render()
    {
        return $this->getTemplate()
            ->parse($this->_templateFile)
            ->process(['element' => $this->getElement()]);
    }
}