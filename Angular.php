<?php

namespace dee\angular;

use Yii;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\FileHelper;

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
    public $templateParam = 'template';

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
    public $jsFile;

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
     *
     * @var string
     */
    public $statePath = '@runtime/angular';

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

    public function init()
    {
        static::$instance = $this;
    }

    public function run()
    {
        $template = Yii::$app->request->get($this->templateParam);
        $params = Yii::$app->controller->actionParams;
        $params[0] = '/' . Yii::$app->controller->getRoute();

        if ($template === null) {
            $routeProvider = [];
            $controllers = [];
            foreach ($this->routes as $path => $route) {
                if (!isset($route['view'], $route['controller'])) {
                    throw new InvalidConfigException('"view" and "controller" of route must be set.');
                }
                $this->controller = $route['controller'];
                $viewName = ArrayHelper::remove($route, 'view');

                $this->setState($viewName);

                $di = ArrayHelper::remove($route, 'di', []);
                $controllers[$this->controller] = $di;

                $params[$this->templateParam] = $viewName;
                $route['templateUrl'] = Url::to($params);
                $r = Json::encode($route);
                $routeProvider[] = "\$routeProvider.when('{$path}',$r);";
                $this->controller = null;
            }

            $routeProvider[] = '$routeProvider.otherwise(' . Json::encode(['redirectTo' => $this->defaultPath]) . ');';

            $this->renderModule();
            $this->renderRouteProvider($routeProvider);
            $this->renderControllers($controllers);
            $this->renderResources();
            $this->renderJsFile();

            echo Html::tag($this->tag, '', ['ng-app' => $this->name, 'ng-view' => true]);
        } else {
            Yii::$app->response->content = $this->getState($template);
            Yii::$app->end();
        }
        static::$instance = null;
    }

    public static function requires($requires)
    {
        static::$instance->requires = array_unique(array_merge(static::$instance->requires, (array) $requires));
    }

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

    protected function renderRouteProvider($routeProvider)
    {
        $view = $this->getView();
        $routeProvider = implode("\n", $routeProvider);
        $js = "{$this->name}.config(['\$routeProvider',function(\$routeProvider){\n{$routeProvider}\n}]);";
        $view->registerJs($js, View::POS_END);
    }

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

    protected static function parseBlockJs($js)
    {
        $jsBlockPattern = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if (preg_match($jsBlockPattern, trim($js), $matches)) {
            $js = trim($matches['block_content']);
        }
        return $js;
    }

    protected function renderJsFile()
    {
        if ($this->jsFile !== null) {
            $js = $this->view->render($this->jsFile, ['angular' => $this]);
            $this->view->registerJs(static::parseBlockJs($js), View::POS_END);
        }
    }

    public static function registerJs($js, $pos = null)
    {
        static::$instance->view->registerJs(static::parseBlockJs($js), $pos? : static::$instance->controller);
    }

    public static function beginScript()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    public static function endScript()
    {
        $js = ob_get_clean();
        static::registerJs($js);
    }
    private $_statePath;

    protected function getStatePath()
    {
        if ($this->_statePath === null) {
            $params = Yii::$app->controller->actionParams;
            $params[0] = '/' . Yii::$app->controller->getRoute();
            $path = sprintf('%x', crc32(serialize($params) . __CLASS__ . $this->getId()));
            $this->_statePath = Yii::getAlias("{$this->statePath}/{$path}");
            FileHelper::createDirectory($this->_statePath);
        }
        return $this->_statePath;
    }

    protected function setState($template)
    {
        $path = $this->getStatePath() . '/' . str_replace(['/', '@'], ['_', ''], $template);
        file_put_contents($path, $this->view->render($template, ['angular' => $this]));
    }

    protected function getState($template)
    {
        $path = $this->getStatePath() . '/' . str_replace(['/', '@'], ['_', ''], $template);
        return file_get_contents($path);
    }
}