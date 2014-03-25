<?php

/**
 * View builder
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command\Utils;

/**
 * View builder
 *
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 *
 * @property array $templates
 */
class ViewBuilder extends FormBuilder
{

    /**
     * @readwrite
     * @var array A list of available templates
     */
    protected $_templates = [
        'index' => 'template/view.index.html.twig',
        'show' => 'template/view.show.html.twig',
        'edit' => 'template/view.edit.html.twig',
        'add' => 'template/view.add.html.twig'
    ];

    /**
     * Returns the controller code for current controller data
     *
     * @param string $name
     * @return string The controller code
     */
    public function getCode($name)
    {
        $template = $this->_templates[$name];
        return $this->getTemplate()
            ->parse($template)
            ->process(
                [
                    'command' => $this->_controllerData,
                    'modelData' => $this->_modelData,
                    'builder' => $this,
                    'elements' => $this->getElementData()
                ]
            );
    }
} 