<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace dee\angular\generators\crud;

use Yii;
use yii\web\Controller;
use yii\gii\CodeFile;
use yii\db\BaseActiveRecord;

/**
 * Generates CRUD
 *
 * @property string $controllerClass The controller class to be generated. This property is
 * read-only.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    public $controllerID;
    public $moduleID;
    public $alsoAsRest = true;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Angular CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(\yii\gii\Generator::rules(), [
            [['moduleID', 'controllerID', 'modelClass', 'baseControllerClass'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'controllerID', 'baseControllerClass', 'indexWidgetType'], 'required'],
            [['modelClass', 'baseControllerClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => BaseActiveRecord::className()]],
            [['baseControllerClass'], 'validateClass', 'params' => ['extends' => Controller::className()]],
            [['controllerID'], 'match', 'pattern' => '/^[a-z][a-z0-9\\-\\/]*$/', 'message' => 'Only a-z, 0-9, dashes (-) and slashes (/) are allowed.'],
            [['controllerClass'], 'filter', 'filter' => function() {
                return $this->getControllerClass();
            }, 'skipOnEmpty' => false],
            [['modelClass'], 'validateModelClass'],
            [['moduleID'], 'validateModuleID'],
            [['enableI18N', 'alsoAsRest'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'controllerID' => 'Controller ID',
            'moduleID' => 'Module ID',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'controllerID' => 'Controller ID should be in lower case and may contain module ID(s) separated by slashes. For example:
                <ul>
                    <li><code>order</code> generates <code>OrderController.php</code></li>
                    <li><code>order-item</code> generates <code>OrderItemController.php</code></li>
                    <li><code>admin/user</code> generates <code>UserController.php</code> under <code>admin</code> directory.</li>
                </ul>',
            'alsoAsRest' => 'When <code>true</code> then controller is also generated as REST',
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
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->getControllerClass(), '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }
        $templatePath = $this->getTemplatePath() . '/views/js';
        foreach (scandir($templatePath) as $file) {
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $jsFile = substr($file, 0, -4);
                $files[] = new CodeFile("$viewPath/js/$jsFile", $this->render("views/js/$file"));
            }
        }

        return $files;
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
     * @inheritdoc
     */
    public function successMessage()
    {
        $route = '/' . $this->controllerID . '/index';
        if (!empty($this->moduleID)) {
            $route = '/' . $this->moduleID . $route;
        }
        $link = \yii\helpers\Html::a('try it now', [$route], ['target' => '_blank']);

        return "The controller has been generated successfully. You may $link.";
    }

    /**
     * @return string the controller class
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
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['alsoAsRest']);
    }
}