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
        'js/dee-angular-rest.js',
        'js/dee-angular-ui.js',
        'js/dee-angular.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'dee\angular\AngularAsset',
        'dee\angular\AngularResourceAsset',
    ];

}