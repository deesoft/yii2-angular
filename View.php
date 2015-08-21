<?php

namespace dee\angular;

/**
 * Description of View
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class View extends \yii\web\View
{
    /**
     * @inheritdoc
     */
    public function registerJs($js, $position = null, $key = null)
    {
        if($position === null){
            if(NgView::$instance && NgView::$instance->controller){
                $position = Angular::$instance->controller;
            }  else {
                $position = self::POS_READY;
            }
        }
        parent::registerJs($js, $position, $key);
    }
}