<?php

namespace dee\angular;

/**
 * AngularRouteAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngucompleteAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dee/angular/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/angucomplete.js',
    ];
    
    public $css = [
        'css/angucomplete.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset',
    ];    
}