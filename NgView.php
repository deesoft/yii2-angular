<?php

namespace dee\angular;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\Inflector;
use yii\web\View as WebView;

/**
 * Description of NgView
 *
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class NgView extends \yii\base\Widget
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
     * @var string|array
     */
    public $otherwise;

    /**
     *
     * @var string
     */
    public $name = 'dApp';

    /**
     * @var boolean If `true` will render attribute `ng-app="appName"` in widget.
     */
    public $useNgApp = true;

    /**
     * @var array
     */
    public $requires = [];

    /**
     * @var string
     */
    public $tag = 'ng-view';

    /**
     *
     * @var string
     */
    public $js;

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
        'dee.angular' => 'dee\angular\DeeAngularAsset',
        'ngRoute' => 'dee\angular\AngularRouteAsset',
        'ngResource' => 'dee\angular\AngularResourceAsset',
    ];
    private $_varName;

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
        $this->_varName = Inflector::variablize($this->name);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $routeProvider = [];
        $controllers = [];
        $view = $this->getView();
        $views = [];
        foreach ($this->routes as $path => $route) {
            if (isset($route['link'])) {
                $link = Json::htmlEncode($route['link']);
                unset($route['link'], $route['view'], $route['controller'], $route['show']);
                $route = Json::htmlEncode($route);
                $path = Json::htmlEncode($path);
                $routeProvider[] = "\$routeProvider.when({$path},angular.extend({},{$this->_varName}.views[{$link}],{$route}));";
            } else {
                if (!isset($route['view'])) {
                    throw new InvalidConfigException('"view" of route must be set.');
                }
                $viewName = ArrayHelper::remove($route, 'view');

                if (empty($route['controller'])) {
                    $route['controller'] = Inflector::camelize($viewName) . 'Ctrl';
                }
                $this->controller = $route['controller'];
                if(isset($route['js'])){
                    $this->renderJs($route['js']);
                    unset($route['js']);
                }
                $route['template'] = $view->render($viewName, ['angular' => $this]);

                $di = ArrayHelper::remove($route, 'di', []);
                $controllers[$this->controller] = $di;
                $show = ArrayHelper::remove($route, 'show', true);
                $views[$path] = $route;

                if ($show) {
                    $path = Json::htmlEncode($path);
                    $routeProvider[] = "\$routeProvider.when({$path},{$this->_varName}.views[{$path}]);";
                }
            }
            $this->controller = null;
        }
        
        if (!empty($this->otherwise)) {
            $route = is_string($this->otherwise) ? ['redirectTo' => $this->otherwise] : $this->otherwise;
            if (isset($route['link'])) {
                $link = Json::htmlEncode($route['link']);
                unset($route['link'], $route['view'], $route['controller']);
                $route = Json::htmlEncode($route);
                $routeProvider[] = "\$routeProvider.otherwise(angular.extend({},{$this->_varName}.views[{$link}],{$route}));";
            } elseif (isset($route['view'])) {
                $viewName = ArrayHelper::remove($route, 'view');

                if (empty($route['controller'])) {
                    $route['controller'] = Inflector::camelize($viewName) . 'Ctrl';
                }
                $this->controller = $route['controller'];
                if(isset($route['js'])){
                    $this->renderJs($route['js']);
                    unset($route['js']);
                }
                $route['template'] = $view->render($viewName, ['angular' => $this]);

                $di = ArrayHelper::remove($route, 'di', []);
                $controllers[$this->controller] = $di;

                $views['otherwise'] = $route;
                $routeProvider[] = "\$routeProvider.otherwise({$this->_varName}.views.otherwise);";
            } else {
                $route = Json::htmlEncode($route);
                $routeProvider[] = "\$routeProvider.otherwise({$route});";
            }
            $this->controller = null;
        }

        $js = [];
        $js[] = "{$this->_varName} = (function(){";
        $js[] = $this->renderModule();
        $js[] = $this->renderViews($views);
        $js[] = $this->renderRouteProvider($routeProvider);
        $js[] = $this->renderControllers($controllers);
        $js[] = $this->renderResources();
        $js[] = "\nreturn {$this->_varName};\n})();";

        $view->registerJs(implode("\n", $js), WebView::POS_END);

        static::$instance = null;
        return Html::tag($this->tag, '', ['ng-app' => $this->useNgApp ? $this->name : false, 'ng-view' => $this->tag != 'ng-view']);
    }

    /**
     * Use to add required module to application
     *
     * @param sting|array $requires
     */
    public function requires($requires)
    {
        $this->requires = array_unique(array_merge($this->requires, (array) $requires));
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
        if (!empty($this->resources)) {
            $this->requires = array_unique(array_merge($this->requires, ['ngResource']));
        }
        $requires = array_unique(array_merge(['ngRoute'], $this->requires));
        AngularAsset::register($view);
        foreach ($requires as $module) {
            if (isset(static::$requireAssets[$module])) {
                $class = static::$requireAssets[$module];
                $class::register($view);
            }
        }
        $js = "{$this->_varName} = angular.module('{$this->name}'," . Json::htmlEncode($requires) . ");";

        if ($this->js !== null) {
            $js .= "\n" . static::parseBlockJs($view->render($this->js));
        }
        return $js;
    }

    protected function renderViews($views)
    {
        return "{$this->_varName}.views = " . Json::htmlEncode($views) . ';';
    }

    /**
     * Render script config for $routeProvider
     * @param array $routeProvider
     */
    protected function renderRouteProvider($routeProvider)
    {
        $routeProvider = implode("\n", $routeProvider);
        return "{$this->_varName}.config(['\$routeProvider',function(\$routeProvider){\n{$routeProvider}\n}]);";
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
        $js = [];
        $view = $this->getView();
        foreach ($controllers as $name => $di) {
            $di = array_unique(array_merge(['$scope', '$injector'], (array) $di));
            $di1 = implode("', '", $di);
            $di2 = implode(", ", $di);
            $function = implode("\n", ArrayHelper::getValue($view->js, $name, []));
            $js[] = "{$this->_varName}.controller('$name',['$di1',\nfunction($di2){\n{$function}\n}]);";
        }
        return implode("\n", $js);
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
        if (!empty($this->resources)) {
            $js = [];
            foreach ($this->resources as $name => $config) {
                $url = Json::htmlEncode($config['url']);
                if (empty($config['paramDefaults'])) {
                    $paramDefaults = '{}';
                } else {
                    $paramDefaults = Json::htmlEncode($config['paramDefaults']);
                }
                if (empty($config['actions'])) {
                    $actions = '{}';
                } else {
                    $actions = Json::htmlEncode($config['actions']);
                }

                $js[] = <<<JS
{$this->_varName}.factory('$name',['\$resource',function(\$resource){
    return \$resource({$url},{$paramDefaults},{$actions});
}]);
JS;
            }
            return implode("\n", $js);
        }
        return '';
    }

    /**
     * Only get script inner of `script` tag.
     * @param string $js
     * @return string
     */
    public static function parseBlockJs($js)
    {
        $jsBlockPattern = '|^<script[^>]*>(?P<block_content>.+?)</script>$|is';
        if (preg_match($jsBlockPattern, trim($js), $matches)) {
            $js = trim($matches['block_content']);
        }
        return $js;
    }

    /**
     * Register script to controller.
     * 
     * @param string $viewFile
     * @param array $params
     * @param integer|string $pos
     */
    public function renderJs($viewFile, $params = [], $pos = null)
    {
        $params['angular'] = $this;
        $js = $this->view->render($viewFile, $params);
        $this->registerJs($js, $pos);
    }

    /**
     * Register script to controller.
     *
     * @param string $js
     * @param integer|string $pos
     */
    public function registerJs($js, $pos = null)
    {
        $pos = $pos ? : ($this->controller ? : WebView::POS_END);
        $this->view->registerJs(static::parseBlockJs($js), $pos);
    }
}