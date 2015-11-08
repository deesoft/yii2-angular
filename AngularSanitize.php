<?php

namespace dee\angular;

/**
 * AngularAriaAsset
 *
 * @author VitProg <vitprog@gmail.com>
 * @since 1.1
 */
class AngularSanitize extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular-sanitize';

    /**
     * @inheritdoc
     */
    public $js = [
        'angular-sanitize.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset'
    ];
}