<?php

namespace dee\angular;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\helpers\ArrayHelper;

/**
 * RestAction
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class RestAction extends \yii\base\Action
{
    /**
     * @var array
     */
    public $patterns = [
        'POST,PUT {id}' => 'update',
        'PATCH {id}' => 'patch',
        'DELETE {id}' => 'delete',
        'GET,HEAD {id}' => 'view',
        'POST' => 'create',
        'GET,HEAD' => 'query',
        'OPTIONS' => 'options',
    ];

    /**
     * @var array
     */
    public $extraPatterns = [];

    /**
     * @var array
     */
    private $_rules = [];

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = 'dee\angular\Serializer';

    /**
     * @var array 
     */
    public $contentNegotiator = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::createObject(ArrayHelper::merge([
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
                ], $this->contentNegotiator))->negotiate();
        
        $patterns = array_merge($this->patterns, $this->extraPatterns);
        foreach ($patterns as $pattern => $action) {
            $rule = $this->createRule($pattern, $action);
            $rule['_sort'] = 10 * count($rule['params']) + count($rule['verbs']);
            $this->_rules[] = $rule;
        }
        ArrayHelper::multisort($this->_rules, '_sort', SORT_DESC);
    }

    /**
     *
     * @param string $pattern
     * @param string $action
     * @return array
     */
    public function createRule($pattern, $action)
    {
        $rule = ['action' => $action, 'params' => []];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        if (preg_match("/^((?:($verbs),)*($verbs))(?:\\s(.*))?$/", $pattern, $matches)) {
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

    /**
     * @inheritdoc
     */
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
                    $result = call_user_func_array([$controller, $rule['action']], $args);
                    return $this->serializeData($result);
                }
            }
        }
    }

    /**
     * Serializes the specified data.
     * The default implementation will create a serializer based on the configuration given by [[serializer]].
     * It then uses the serializer to serialize the given data.
     * @param mixed $data the data to be serialized
     * @return mixed the serialized data.
     */
    protected function serializeData($data)
    {
        return Yii::createObject($this->serializer)->serialize($data);
    }
}