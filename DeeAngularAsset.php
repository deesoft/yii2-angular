<?php

namespace dee\angular;

/**
 * AngularAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DeeAngularAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dee/angular/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/yii.angular.js',
        'js/dee.angular.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'dee\angular\AngularAsset',
        'dee\angular\AngularResourceAsset',
    ];

}