<?php

namespace dee\angular;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Controller
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Controller extends \yii\web\Controller
{
    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = 'yii\rest\Serializer';

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    public function actions()
    {
        return[
            'resource' => [
                'class' => __NAMESPACE__ . '\ResourceAction',
            ],
            'template' => [
                'class' => 'yii\web\ViewAction',
                'layout' => false,
                'viewPrefix' => '',
                'defaultView' => 'list',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'text/html' => Response::FORMAT_HTML,
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
                'except' => $this->exceptNegoitate(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        return $this->serializeData($result);
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
        if (is_array($this->serializer) && !isset($this->serializer['class'])) {
            $this->serializer['class'] = 'yii\rest\Serializer';
        }
        return Yii::createObject($this->serializer)->serialize($data);
    }

    public function exceptNegoitate()
    {
        return [
            'index',
            'template',
        ];
    }
}