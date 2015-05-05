<?php

namespace dee\angular;

/**
 * Description of AngularValidation
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularValidation extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dee/angular/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/angular-validation.min.js',
        'js/angular-validation-rule.min.js',
    ];
    public $depends = [
        'dee\angular\AngularAsset',
    ];
}