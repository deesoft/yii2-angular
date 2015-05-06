<?php

namespace dee\angular;

use Yii;

/**
 * Description of Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap implements \yii\base\BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::$container->set('yii\web\View', 'dee\angular\View');
    }
}