<?php

namespace dee\angular;

/**
 * AngularResourceAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularResourceAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular-resource';

    /**
     * @inheritdoc
     */
    public $js = [
        'angular-resource.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset'
    ];
}