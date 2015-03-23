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
        'PUT,PATCH {id}' => 'update',
        'DELETE {id}' => 'delete',
        'GET,HEAD {id}' => 'view',
        'POST' => 'create',
        'GET,HEAD' => 'query',
        'OPTIONS' => 'options',
    ];
    public $extraPatterns = [];
    private $_rules = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $patterns = array_merge($this->patterns, $this->extraPatterns);
        foreach ($patterns as $pattern => $action) {
            $this->_rules[] = $this->createRule($pattern, $action);
        }
        usort($this->_rules, function($a, $b) {
            $ca = count($a['params']);
            $cb = count($b['params']);
            if ($ca > $cb) {
                return -1;
            } elseif ($ca < $cb) {
                return 1;
            } else {
                return count($a['verbs']) > count($b['verbs']) ? -1 : 1;
            }
        });
    }

    public function createRule($pattern, $action)
    {
        $rule = ['action' => $action, 'params' => []];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        if (preg_match("/^((?:($verbs),)*($verbs))(?:\\s+(.*))?$/", $pattern, $matches)) {
            $rule['verbs'] = explode(',', $matches[1]);
            if (isset($matches[4]) && preg_match_all('/\\{(.*?)\\}/', $matches[4], $params) && isset($params[1])) {
                foreach ($params[1] as $param) {
                    if (($pos = strpos($param, '=')) === false) {
                        $rule['params'][] = $param;
                    } else {
                        $rule['params'][substr($param, 0, $pos)] = substr($param, $pos + 1);
                    }
                }
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
                $match = true;
                $args = [];
                foreach ($rule['params'] as $param => $value) {
                    if (is_int($param) && isset($params[$value])) {
                        $args[] = $params[$value];
                    } elseif (isset($params[$param]) && $params[$param] === $value) {
                        $args[] = $value;
                    } else {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    return call_user_func_array([$controller, $rule['action']], $args);
                }
            }
        }
    }
}