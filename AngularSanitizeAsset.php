<?php

namespace dee\angular;

/**
 * AngularSanitizeAsset
 *
 * @author VitProg <vitprog@gmail.com>
 */
class AngularSanitizeAsset extends \yii\web\AssetBundle
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