<?php

namespace dee\angular;

/**
 * AngularBootstrapAsset
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AngularBootstrapAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/angular-bootstrap';

    /**
     * @inheritdoc
     */
    public $js = [
        'ui-bootstrap-tpls.js',
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
            array_push($this->depends, 'dee\angular\BootstrapAsset');
        }
    }
}