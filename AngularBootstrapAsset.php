<?php

namespace dee\angular;

/**
 * AngularRouteAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularBootstrapAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@dee/angular/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/ui-bootstrap-tpls-0.12.0.min.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'dee\angular\AngularAsset'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (class_exists('yii\bootstrap\BootstrapAsset')) {
            array_push($this->depends, 'yii\bootstrap\BootstrapAsset');
        } else {
            $this->css = [
                'css/bootstrap.min.css'
            ];
        }
    }
}