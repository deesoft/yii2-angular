<?php

namespace dee\angular;

/**
 * BootstrapAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class BootstrapAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dee/angular/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/bootstrap.min.css',
    ];

}