<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace dee\angular\generators\mvc;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Html;
use yii\base\Model;
use yii\web\Controller;

/**
 * This generator will generate a controller and one or a few action view files.
 *
 * @property array $actionIDs An array of action IDs entered by the user. This property is read-only.
 * @property string $controllerClass The controller class name without the namespace part. This property is
 * read-only.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 */
class Generator extends \yii\gii\Generator
{
    /**
     * @var string The controller ID (without the module ID prefix)
     */
    public $controllerID;

    /**
     * @var string the base class of the controller
     */
    public $baseControllerClass = 'yii\web\Controller';

    /**
     * @var string class name of model
     */
    public $modelClass;

    /**
     *
     * @var string module ID
     */
    public $moduleID;

    /**
     * @var string list of action IDs separated by commas or spaces
     */
    public $actions = 'index';

    /**
     * @var string list of action IDs separated by commas or spaces
     */
    public $formActions;

    /**
     *
     * @var string 
     */
    public $scenarioName;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'MDM MVC Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator helps you to quickly generate a new controller class,
            one or several controller actions and their corresponding views.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['moduleID', 'controllerID', 'actions', 'formActions', 'baseControllerClass'], 'filter', 'filter' => 'trim'],
            [['controllerID', 'baseControllerClass'], 'required'],
            [['controllerID'], 'match', 'pattern' => '/^[a-z][a-z0-9\\-\\/]*$/', 'message' => 'Only a-z, 0-9, dashes (-) and slashes (/) are allowed.'],
            [['actions', 'formActions'], 'match', 'pattern' => '/^[a-z][a-z0-9\\-,\\s]*$/', 'message' => 'Only a-z, 0-9, dashes (-), spaces and commas are allowed.'],
            [['baseControllerClass', 'modelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
            [['scenarioName'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => Model::className()]],
            [['moduleID'], 'validateModuleID'],
        ]);
    }

    /**
     * Checks if model ID is valid
     */
    public function validateModuleID()
    {
        if (!empty($this->moduleID)) {
            $module = Yii::$app->getModule($this->moduleID);
            if ($module === null) {
                $this->addError('moduleID', "Module '{$this->moduleID}' does not exist.");
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'baseControllerClass' => 'Base Controller Class',
            'controllerID' => 'Controller ID',
            'actions' => 'Action IDs',
            'moduleID' => 'Module ID',
            'formActions' => 'Form Action IDs',
            'scenarioName' => 'Scenario',
        ];
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return [
            'controller.php',
            'view.php',
        ];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return ['moduleID', 'baseControllerClass'];
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return [
            'controllerID' => 'Controller ID should be in lower case and may contain module ID(s) separated by slashes. For example:
                <ul>
                    <li><code>order</code> generates <code>OrderController.php</code></li>
                    <li><code>order-item</code> generates <code>OrderItemController.php</code></li>
                    <li><code>admin/user</code> generates <code>UserController.php</code> under <code>admin</code> directory.</li>
                </ul>',
            'actions' => 'Provide one or multiple action IDs to generate empty action method(s) in the controller. Separate multiple action IDs with commas or spaces.
                Action IDs should be in lower case. For example:
                <ul>
                    <li><code>index</code> generates <code>actionIndex()</code></li>
                    <li><code>create-order</code> generates <code>actionCreateOrder()</code></li>
                </ul>',
            'modelClass' => 'This is the model class for collecting the form input. You should provide a fully qualified class name, e.g., <code>app\models\Post</code>.',
            'scenarioName' => 'This is the scenario to be used by the model when collecting the form input. If empty, the default scenario will be used.',
            'formActions' => 'Provide one or multiple action IDs to generate empty action method(s) in the controller. Separate multiple action IDs with commas or spaces.
                Action IDs should be in lower case. For example:
                <ul>
                    <li><code>update</code> generates <code>actionUpdate()</code></li>
                    <li><code>create-order</code> generates <code>actionCreateOrder()</code></li>
                </ul>',
            'moduleID' => 'This is the ID of the module that the generated controller will belong to.
                If not set, it means the controller will belong to the application.',
            'baseControllerClass' => 'This is the class that the new controller class will extend from. Please make sure the class exists and can be autoloaded.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function successMessage()
    {
        $actions = $this->getActionIDs();
        if (in_array('index', $actions)) {
            $route = '/' . $this->controllerID . '/index';
        } else {
            $route = '/' . $this->controllerID . '/' . reset($actions);
        }
        if (!empty($this->moduleID)) {
            $route = '/' . $this->moduleID . $route;
        }
        $link = Html::a('try it now', [$route], ['target' => '_blank']);

        return "The controller has been generated successfully. You may $link.";
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [];

        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files[] = new CodeFile($controllerFile, $this->render('controller.php'));

        $viewPath = $this->getViewPath();

        foreach ($this->getActionIDs() as $action) {
            if ($this->isFormAction($action)) {
                $files[] = new CodeFile("$viewPath/$action.php", $this->render('form.php', ['action' => $action]));
            } else {
                $files[] = new CodeFile("$viewPath/$action.php", $this->render('view.php', ['action' => $action]));
            }
        }
        if (!empty($this->modelClass)) {
            $files[] = new CodeFile("$viewPath/_form.php", $this->render('_form.php'));
        }

        return $files;
    }

    /**
     * Normalizes [[actions]] into an array of action IDs.
     * @return array an array of action IDs entered by the user
     */
    public function getActionIDs()
    {
        $actions = preg_split('/[\s,]+/', $this->actions, -1, PREG_SPLIT_NO_EMPTY);
        if (!empty($this->modelClass) && !empty($this->formActions)) {
            $actions = array_merge($actions, preg_split('/[\s,]+/', $this->formActions, -1, PREG_SPLIT_NO_EMPTY));
        }
        $actions = array_unique($actions);

        return $actions;
    }

    /**
     * Check if action use for render form
     * @param string $action
     * @return boolean
     */
    public function isFormAction($action)
    {
        $formActionss = preg_split('/[\s,]+/', $this->formActions, -1, PREG_SPLIT_NO_EMPTY);
        return !empty($this->modelClass) && in_array($action, $formActionss);
    }

    /**
     * @return string the controller class name without the namespace part.
     */
    public function getControllerClass()
    {
        $module = empty($this->moduleID) ? Yii::$app : Yii::$app->getModule($this->moduleID);
        $id = $this->controllerID;
        $pos = strrpos($id, '/');
        if ($pos === false) {
            $prefix = '';
            $className = $id;
        } else {
            $prefix = substr($id, 0, $pos + 1);
            $className = substr($id, $pos + 1);
        }

        $className = str_replace(' ', '', ucwords(str_replace('-', ' ', $className))) . 'Controller';
        $className = ltrim($module->controllerNamespace . '\\' . str_replace('/', '\\', $prefix) . $className, '\\');

        return $className;
    }

    /**
     * @return string the action view file path
     */
    public function getViewPath()
    {
        $module = empty($this->moduleID) ? Yii::$app : Yii::$app->getModule($this->moduleID);

        return $module->getViewPath() . '/' . $this->controllerID;
    }

    /**
     * @return array list of safe attributes of [[modelClass]]
     */
    public function getModelAttributes()
    {
        /* @var $model Model */
        $model = new $this->modelClass();
        if (!empty($this->scenarioName)) {
            $model->setScenario($this->scenarioName);
        }

        return $model->safeAttributes();
    }
}