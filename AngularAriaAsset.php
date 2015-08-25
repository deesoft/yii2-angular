<?php

namespace dee\angular;

/**
 * AngularAnimateAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.1
 */
class AngularAriaAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular-aria';

    /**
     * @inheritdoc
     */
    public $js = [
        'angular-aria.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset'
    ];
}