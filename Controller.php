<?php

namespace dee\angular;

use yii\web\Response;
use yii\helpers\ArrayHelper;
/**
 * Controller
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Controller extends \yii\rest\Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator'], $behaviors['rateLimiter']);
        $behaviors['contentNegotiator'] = ArrayHelper::merge($behaviors['contentNegotiator'], [
            'formats'=>[
                'text/html'=>Response::FORMAT_HTML,
            ],
            'except'=>$this->exceptNegoitate(),
        ]);
        return $behaviors;
    }

    public function actionPartial($view = 'list')
    {
        return $this->renderPartial($view);
    }

    public function exceptNegoitate()
    {
        return [
            'index',
            'partial'
        ];
    }
}