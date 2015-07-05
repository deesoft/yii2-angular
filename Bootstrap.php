<?php

namespace dee\angular;

use Yii;
use yii\web\Application;
use yii\web\Request;

/**
 * Description of Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap implements \yii\base\BootstrapInterface
{

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            Yii::$container->set('yii\web\View', 'dee\angular\View');
            $request = $app->has('request', true) ? $app->get('request') : $app->components['request'];
            if ($request instanceof Request) {
                if (!isset($request->parsers['application/json']) && !isset($request->parsers['*'])) {
                    $request->parsers['application/json'] = 'yii\web\JsonParser';
                }
            } elseif (is_array($request)) {
                if (!isset($request['parsers']['application/json']) && !isset($request['parsers']['*'])) {
                    $request['parsers']['application/json'] = 'yii\web\JsonParser';
                    $app->set('request', $request);
                }
            }
        }
    }
}