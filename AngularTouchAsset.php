<?php

namespace dee\angular;

/**
 * AngularTouchAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.1
 */
class AngularTouchAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular-touch';

    /**
     * @inheritdoc
     */
    public $js = [
        'angular-touch.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset'
    ];
}