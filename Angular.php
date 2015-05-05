<?php

namespace dee\angular;

use Yii;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\Inflector;

/**
 * Description of NgView
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Angular extends \yii\base\Widget
{
    /**
     *
     * @var array
     */
    public $routes = [];

    /**
     *
     * @var array
     */
    public $resources = [];

    /**
     *
     * @var string
     */
    public $defaultPath = '/';

    /**
     *
     * @var string
     */
    public $name = 'dApp';

    /**
     *
     * @var array
     */
    public $requires = [];

    /**
     *
     * @var string
     */
    public $tag = 'div';

    /**
     *
     * @var string
     */
    public $controller;

    /**
     * @var array
     */
    public static $requireAssets = [
        'ui.bootstrap' => 'dee\angular\AngularBootstrapAsset',
        'angucomplete' => 'dee\angular\AngucompleteAsset',
        'validation' => 'dee\angular\AngularValidation',
        'validation.rule' => 'dee\angular\AngularValidation',
    ];

    /**
     *
     * @var static 
     */
    public static $instance;

    /**
     * @inheritdoc
     */
    public function init()
    {
        static::$instance = $this;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $routeProvider = [];
        $controllers = [];
        $view = $this->getView();

        foreach ($this->routes as $path => $route) {
            if (!isset($route['view'])) {
                throw new InvalidConfigException('"view" of route must be set.');
            }
            $viewName = ArrayHelper::remove($route, 'view');

            if (empty($route['controller'])) {
                $route['controller'] = Inflector::camelize($viewName) . 'Ctrl';
            }
            $this->controller = $route['controller'];
            $route['template'] = $view->render($viewName, ['angular' => $this]);

            $di = ArrayHelper::remove($route, 'di', []);
            $controllers[$this->controller] = $di;

            $routeProvider[] = "\$routeProvider.when('{$path}'," . Json::encode($route) . ");";
            $this->controller = null;
        }

        $routeProvider[] = '$routeProvider.otherwise(' . Json::encode(['redirectTo' => $this->defaultPath]) . ');';

        $this->renderModule();
        $this->renderRouteProvider($routeProvider);
        $this->renderControllers($controllers);
        $this->renderResources();

        echo Html::tag($this->tag, '', ['ng-app' => $this->name, 'ng-view' => true]);

        static::$instance = null;
    }

    /**
     * Use to add required module to application
     *
     * @param sting|array $requires
     */
    public static function requires($requires)
    {
        static::$instance->requires = array_unique(array_merge(static::$instance->requires, (array) $requires));
    }

    /**
     * Render script create module. The result are
     * ```javascript
     * appName = angular.module('appName',[requires,...]);
     * ```
     */
    protected function renderModule()
    {
        $view = $this->getView();
        $requires = array_unique(array_merge(['ngRoute'], $this->requires));
        AngularAsset::register($view);
        foreach ($requires as $module) {
            if (isset(static::$requireAssets[$module])) {
                $class = static::$requireAssets[$module];
                $class::register($view);
            }
        }
        $js = "{$this->name} = angular.module('{$this->name}'," . Json::encode($requires) . ");";
        $view->registerJs($js, View::POS_END);
    }

    /**
     * Render script config for $routeProvider
     * @param array $routeProvider
     */
    protected function renderRouteProvider($routeProvider)
    {
        $view = $this->getView();
        $routeProvider = implode("\n", $routeProvider);
        $js = "{$this->name}.config(['\$routeProvider',function(\$routeProvider){\n{$routeProvider}\n}]);";
        $view->registerJs($js, View::POS_END);
    }

    /**
     * Render script create controllers
     * ```javascript
     * appName.controller('CtrlName',['$scope',...,
     *     function($scope,...){
     *         ...
     *     }]);
     * ```
     * @param array $controllers
     */
    protected function renderControllers($controllers)
    {
        $view = $this->getView();
        foreach ($controllers as $name => $di) {
            $di = array_unique(array_merge(['$scope', '$injector'], (array) $di));
            $di1 = implode("', '", $di);
            $di2 = implode(", ", $di);
            $js = implode("\n", ArrayHelper::getValue($view->js, $name, []));
            $js = "{$this->name}.controller('$name',['$di1',\nfunction($di2){\n{$js}\n}]);";
            $view->registerJs($js, View::POS_END);
        }
    }

    /**
     * Render script resource
     * ```javascript
     * appName.factory(ResName,['$resource',function($resource){
     *     return ...;
     * }]);
     * ```
     */
    protected function renderResources()
    {
        $view = $this->getView();
        foreach ($this->resources as $name => $config) {
            $url = Json::encode($config['url']);
            if (empty($config['paramDefaults'])) {
                $paramDefaults = '{}';
            } else {
                $paramDefaults = Json::encode($config['paramDefaults']);
            }
            if (empty($config['actions'])) {
                $actions = '{}';
            } else {
                $actions = Json::encode($config['actions']);
            }

            $js = <<<JS
{$this->name}.factory('$name',['\$resource',function(\$resource){
    return \$resource({$url},{$paramDefaults},{$actions});
}]);
JS;
            $view->registerJs($js, View::POS_END);
        }
    }

    /**
     * Only get script inner of `script` tag.
     * @param string $js
     * @return string
     */
    protected static function parseBlockJs($js)
    {
        $jsBlockPattern = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if (preg_match($jsBlockPattern, trim($js), $matches)) {
            $js = trim($matches['block_content']);
        }
        return $js;
    }

    /**
     * Register script to controller.
     * @param string $js
     * @param integer|string $pos
     */
    public static function registerJs($js, $pos = null)
    {
        $pos = $pos ? : (static::$instance->controller ? : View::POS_END);
        static::$instance->view->registerJs(static::parseBlockJs($js), $pos);
    }

    /**
     * Begin script block
     */
    public static function beginScript()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * End script block
     */
    public static function endScript()
    {
        $js = ob_get_clean();
        static::registerJs($js);
    }
}