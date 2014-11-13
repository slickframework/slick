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

use Slick\Database;
use Slick\Mvc\Scaffold\Form;
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
     * @readwrite
     * @var string
     */
    protected $_basePath;

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

        $name = Text::camelCaseToSeparator(end($nameParts));
        $name = explode(' ', $name);

        $singular = ucfirst(Text::singular(strtolower(end($name))));
        array_pop($name);
        $name[] = $singular;
        $name = implode('', $name);

        $this->set('modelPlural', end($nameParts));
        $this->set('modelSingular', lcfirst($name));
        $this->set('basePath', $this->_basePath);
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
                'response' => $instance->response,
                'view' => $instance->view,
                'layout' => $instance->layout,
                'basePath' => $instance->basePath
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
                ucfirst($this->get('modelSingular'))
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
        $controllerName = end($nameParts);
        $nameParts = Text::camelCaseToSeparator($controllerName, '#');
        $nameParts = explode('#', $nameParts);

        $final = Text::plural(strtolower(array_pop($nameParts)));
        $nameParts[] = ucfirst($final);
        $this->set('modelPlural', lcfirst(implode('', $nameParts)));
        $this->set('modelSingular', lcfirst($controllerName));
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

    /**
     * Handles the request to add page
     */
    public function add()
    {
        $this->view = 'scaffold/add';
        $form = new Form(
            "add-{$this->get('modelSingular')}", $this->getDescriptor()
        );
        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                try {
                    $modelClass = $this->getModelName();
                    /** @var Model $model */
                    $model = new $modelClass($form->getValues());
                    $model->save();
                    $name = ucfirst($this->get('modelSingular'));
                    $this->addSuccessMessage(
                        "{$name} successfully created."
                    );
                    $pmk = $model->getPrimaryKey();
                    $this->redirect(
                        $this->_basePath .'/'.
                        $this->get('modelPlural').'/show/'.$model->$pmk
                    );
                    return;
                } catch (Database\Exception $exp) {
                    $this->addErrorMessage(
                        "Error while saving {$this->get('modelSingular')}} " .
                        "data: {$exp->getMessage()}"
                    );
                }
            } else {
                $this->addErrorMessage(
                    "Cannot save {$this->get('modelSingular')}. " .
                    "Please correct the errors bellow."
                );
            }
        }
        $descriptor =  $this->getDescriptor();
        $this->set(compact('form', 'descriptor'));
    }

    /**
     * Handles the request to edit page
     *
     * @param int $recordId
     */
    public function edit($recordId = 0)
    {
        $this->view = 'scaffold/edit';
        $recordId = StaticFilter::filter('number', $recordId);

        /** @var Model $record */
        $record = call_user_func_array(
            [$this->getModelName(), 'get'],
            [$recordId]
        );

        if (is_null($record)) {
            $this->addWarningMessage(
                "The {$this->get('modelSingular')} with the provided key ".
                "does not exists."
            );

            $this->redirect($this->_basePath .'/'.$this->get('modelPlural'));
            return;
        }

        $form = new Form(
            "edit-{$this->get('modelSingular')}", $this->getDescriptor()
        );

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());
            if ($form->isValid()) {
                try {
                    $modelClass = $this->getModelName();
                    /** @var Model $model */
                    $model = new $modelClass($form->getValues());
                    $model->save();
                    $name = ucfirst($this->get('modelSingular'));
                    $this->addSuccessMessage(
                        "{$name} successfully updated."
                    );
                    $pmk = $model->getPrimaryKey();
                    $this->redirect(
                        $this->_basePath .'/'.
                        $this->get('modelPlural').'/show/'.$model->$pmk
                    );
                    return;
                } catch (Database\Exception $exp) {
                    $this->addErrorMessage(
                        "Error while saving {$this->get('modelSingular')}} " .
                        "data: {$exp->getMessage()}"
                    );
                }
            } else {
                $this->addErrorMessage(
                    "Cannot save {$this->get('modelSingular')}. " .
                    "Please correct the errors bellow."
                );
            }
        } else {
            $form->setData($record->asArray());
        }
        $descriptor =  $this->getDescriptor();
        $this->set(compact('form', 'record', 'descriptor'));
    }

    /**
     * Handles the request to delete a record
     */
    public function delete()
    {
        if ($this->request->isPost()) {
            $recordId = StaticFilter::filter(
                'text',
                $this->request->getPost('id')
            );

            $record = call_user_func_array(
                [$this->getModelName(), 'get'],
                [$recordId]
            );

            if (is_null($record)) {
                $this->addWarningMessage(
                    "The {$this->get('modelSingular')} with the provided key ".
                    "does not exists."
                );
            } else {
                if ($record->delete()) {
                    $this->addSuccessMessage(
                        "The {$this->get('modelSingular')} was successfully " .
                        "deleted."
                    );
                }
            }
        }
        return $this->redirect($this->_basePath .'/'.$this->get('modelPlural'));
    }
}
