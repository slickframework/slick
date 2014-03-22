<?php

/**
 * Form builder
 * @package   Slick\Mvc\Command\Utils
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

namespace Slick\Mvc\Command\Utils;

use Slick\Common\Base;
use Slick\Common\Inspector\TagList;
use Slick\Mvc\Libs\Utils\ModelData;
use Slick\Mvc\Model;
use Slick\Template\Engine\Twig;
use Slick\Template\Template;

/**
 * Class FormBuilder
 * @package Slick\Mvc\Command\Utils
 */
class FormBuilder extends Base
{

    /**
     * @readwrite
     * @var ControllerData
     */
    protected $_controllerData;

    /**
     * @readwrite
     * @var ModelData Model instance
     */
    protected $_modelData;

    /**
     * @readwrite
     * @var Model Model instance
     */
    protected $_model;

    /**
     * @readwrite
     * @var Twig Template engine
     */
    protected $_template;

    /**
     * Overrides default constructor to ensure controller data is set
     *
     * @param ControllerData $data
     * @param array $options
     */
    public function __construct(ControllerData $data, $options = [])
    {
        parent::__construct($options);
        $this->_controllerData = $data;
        $model = $data->modelName;
        $this->_model = new $model();
        $this->_modelData = new ModelData($this->_model);
    }

    /**
     * Returns the form class content for the current controller data
     *
     * @return string Form class code
     */
    public function getCode()
    {
        return $this->getTemplate()
        ->parse('template/form.php.twig')
        ->process(
            [
                'command' => $this->_controllerData,
                'modelData' => $this->_modelData,
                'builder' => $this,
                'elements' => $this->getElementData()
            ]
        );
    }

    /**
     * Template used to render the code
     *
     * @return Twig|\Slick\Template\EngineInterface
     */
    public function getTemplate()
    {
        if (is_null($this->_template)) {
            Template::addPath(dirname(dirname(__DIR__)) .'/'. 'Views');
            $template = new Template(['engine' => 'twig']);
            $this->_template = $template->initialize();
        }
        return $this->_template;
    }

    public function getElementData()
    {
        $properties = $this->_modelData->getPropertyList();
        $meta = array();
        foreach ($properties as $name => $property) {
            $meta[trim($name, '_')] = [
                'type' => $this->getType($property),
                'label' => ucfirst(trim($name, '_')),
                'validate' => $this->getValidation($property),
                'filter' => $this->getFilters($property)
            ];
        }
        return $meta;
    }

    public function getType(TagList $meta)
    {
        if ($meta->hasTag('@belongsTo')) {
            return "select";
        }

        $hasType = $meta->getTag('@column')->value->check('type');
        if ($hasType) {
            $colType = $meta->getTag('@column')->value['type'];
        } else {
            print_r($meta->getTag('@column')->value['type']);
            $colType = 'text';
        }

        switch (strtolower($colType)) {
            case 'boolean':
                $type = 'checkbox';
                break;

            case 'datetime':
                $type = 'dateTime';
                break;

            case 'text':
                $type = 'text';
                if ($meta->getTag('@column')->value->check('size')) {
                    $colSize = $meta->getTag('@column')->value['size'];
                    if ($colSize == 'big' || $colSize == 'medium') {
                        $type = 'area';
                    }
                }
                break;

            default:
                $type = 'text';
        }

        return $type;
    }

    public function getValidation(TagList $meta)
    {
        $validations = [];
        if ($meta->hasTag('@validate')) {
            $validations = [];
            $tag = $meta->getTag('@validate');
            $validations[] = $tag->value;

            if (is_a($tag->value, 'Slick\Common\Inspector\TagValues')) {
                $validations = $tag->value->getArrayCopy();
            }
        }
        return $validations;
    }

    public function getFilters(TagList $meta)
    {
        $validations = [];
        if ($meta->hasTag('@filter')) {
            $validations = [];
            $tag = $meta->getTag('@filter');
            $validations[] = $tag->value;

            if (is_a($tag->value, 'Slick\Common\Inspector\TagValues')) {
                $validations = $tag->value->getArrayCopy();
            }
        }
        return $validations;
    }

    public function getClassName()
    {
        $name = ucfirst($this->_controllerData->getModelSimpleName());
        $name .= 'Form';
        return $name;
    }
}