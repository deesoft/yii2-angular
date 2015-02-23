<?php

namespace dee\angular;

use Yii;

/**
 * Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap implements \yii\base\BootstrapInterface
{

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        //Yii::$container->set('yii\web\JsonResponseFormatter', 'dee\angular\tools\ResponseFormatter');
    }
}