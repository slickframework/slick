<?php

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Slick\Mvc;

use Slick\Utility\Text;
use Slick\Orm\Sql\Select;
use Slick\Template\Template;
use Slick\Orm\Entity\Manager;
use Slick\Filter\StaticFilter;
use Slick\Mvc\Model\Descriptor;
use Slick\Mvc\Libs\Utils\Pagination;

/**
 * Scaffold controller
 *
 * @package   Slick\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Controller $controller
 * @property string $scaffoldControllerName
 * @property string $modelName
 * @property Descriptor $descriptor
 *
 * @method Controller getController() Returns the controller being scaffold
 */
class Scaffold extends Controller
{

    /**
     * @readwrite
     * @var Controller
     */
    protected $_controller;

    /**
     * @readwrite
     * @var string
     */
    protected $_modelName;

    /**
     * @readwrite
     * @var string
     */
    protected $_scaffoldControllerName;

    /**
     * @readwrite
     * @var Descriptor
     */
    protected $_descriptor;

    /**
     * Set common variables for views
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        $nameParts = explode("\\", get_class($this->_controller));
        $this->_scaffoldControllerName = end($nameParts);
        $this->set('modelPlural', strtolower(end($nameParts)));
        $this->set(
            'modelSingular',
            Text::singular(strtolower(end($nameParts)))
        );
        Template::appendPath(__DIR__ . '/Views');
    }

    /**
     * Creates a new scaffold controller
     *
     * @param Controller $instance
     * @param array $options
     *
     * @return self
     */
    public static function getScaffoldController(
        Controller $instance, $options = [])
    {

        $options = array_merge(
            [
                'controller' => $instance,
                'request' => $instance->request,
                'response' => $instance->response
            ],
            $options
        );
        return new static($options);
    }

    /**
     * Returns the model class name
     *
     * @return string
     */
    public function getModelName()
    {
        if (is_null($this->_modelName)) {
            $this->setModelName('Models\\' .
                ucfirst(
                    Text::singular(
                        strtolower($this->_scaffoldControllerName)
                    )
                )
            );
        }
        return $this->_modelName;
    }

    /**
     * Sets model name
     *
     * @param string $name
     *
     * @return self
     */
    public function setModelName($name)
    {
        $this->_modelName = $name;
        $nameParts = explode("\\", $name);
        $controllerName = strtolower(end($nameParts));
        $this->set('modelPlural', strtolower(Text::plural($controllerName)));
        $this->set(
            'modelSingular',
            strtolower(Text::singular($controllerName))
        );
        return $this;
    }

    /**
     * Returns model descriptor
     *
     * @return Descriptor
     */
    public function getDescriptor()
    {
        if (is_null($this->_descriptor)) {
            $this->_descriptor = new Descriptor(
                [
                    'descriptor' => Manager::getInstance()
                        ->get($this->getModelName())
                ]
            );
        }
        return $this->_descriptor;
    }

    /**
     * Handles the request to display index page
     */
    public function index()
    {
        $pagination = new Pagination();
        $pattern = StaticFilter::filter(
            'text',
            $this->getController()->request->getQuery('pattern', null)
        );
        $this->view = 'scaffold/index';
        $descriptor =  $this->getDescriptor();

        /** @var Select $query */
        $query = call_user_func_array([$this->getModelName(), 'find'], []);
        $field = $descriptor->getDisplayField();
        $tableName = $descriptor->getDescriptor()->getEntity()->getTableName();
        $query->where(
            [
                "{$tableName}.{$field} LIKE :pattern" => [
                    ':pattern' => "%{$pattern}%"
                ]
            ]
        );
        $pagination->setTotal($query->count());
        $query->limit(
            $pagination->rowsPerPage,
            $pagination->offset
        );
        $records = $query->all();
        $this->set(compact('pagination', 'records', 'pattern', 'descriptor'));
    }

    /**
     * Handles the request to display show page
     *
     * @param int $recordId
     */
    public function show($recordId = 0)
    {
        $this->view = 'scaffold/show';
        $recordId = StaticFilter::filter('number', $recordId);

        $record = call_user_func_array(
            [$this->getModelName(), 'get'],
            [$recordId]
        );

        if (is_null($record)) {
            $this->addWarningMessage(
                    "The {$this->get('modelSingular')} with the provided key ".
                    "does not exists."
                );

            $this->redirect($this->get('modelPlural'));
            return;
        }
        $descriptor =  $this->getDescriptor();
        $this->set(compact('record', 'descriptor'));
    }
}
