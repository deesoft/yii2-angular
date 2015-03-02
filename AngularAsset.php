<?php

namespace dee\angular;

/**
 * AngularAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dee/angular/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/angular.min.js',
        'js/angular-route.min.js',
        'js/angular-resource.min.js',
        'js/mdm.angular.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}