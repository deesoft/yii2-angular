<?php

namespace dee\angular;

use Yii;
use yii\web\Application;
use yii\base\BootstrapInterface;

/**
 * Description of Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap implements BootstrapInterface
{

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            Yii::$container->set('yii\web\View', 'dee\angular\View');
        }
    }
}