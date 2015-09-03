<?php

namespace dee\angular;

/**
 * AngularAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DeeAngularRestAsset extends \yii\web\AssetBundle
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
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularResourceAsset',
    ];

}