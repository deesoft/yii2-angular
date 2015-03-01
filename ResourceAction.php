<?php

namespace dee\angular;

use Yii;

/**
 * ResourceAction
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ResourceAction extends \yii\base\Action
{
    public $patterns = [
        'PUT,PATCH id' => 'update',
        'DELETE id' => 'delete',
        'GET,HEAD id' => 'view',
        'POST' => 'create',
        'GET,HEAD' => 'query',
        'id' => 'options',
        '' => 'options',
    ];
    public $extraPatterns = [];
    private $_rules = [];

    public function init()
    {
        $patterns = array_merge($this->patterns, $this->extraPatterns);
        foreach ($patterns as $pattern => $action) {
            $this->_rules[] = $this->createRule($pattern, $action);
        }
    }

    public function createRule($pattern, $action)
    {
        $rule = [
            'action' => $action,
        ];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        if (preg_match("/^((?:($verbs),)*($verbs))(?:\\s+(.*))?$/", $pattern, $matches)) {
            $rule['verbs'] = explode(',', $matches[1]);
            if (isset($matches[4])) {
                $rule['param'] = $matches[4];
            }
        } else {
            $rule['verbs'] = [];
        }
        return $rule;
    }

    public function run()
    {
        $controller = $this->controller;
        $method = Yii::$app->request->getMethod();
        $params = Yii::$app->request->getQueryParams();
        foreach ($this->_rules as $rule) {
            if (empty($rule['verbs']) || in_array($method, $rule['verbs'])) {
                if (isset($rule['param']) && isset($params[$rule['param']])) {
                    return call_user_func([$controller, $rule['action']], $params[$rule['param']]);
                } elseif (!isset($rule['param'])) {
                    return call_user_func([$controller, $rule['action']]);
                }
            }
        }
    }
}