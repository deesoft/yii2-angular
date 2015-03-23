<?= '<?php' ?>

use yii\web\View;
use dee\angular\JsBlock;
use dee\angular\AngularAsset;
use dee\angular\AngularBootstrapAsset;

/* @var $this yii\web\View */
AngularAsset::register($this);
AngularBootstrapAsset::register($this);
?>
<?= '<?php'?> JsBlock::widget(['pos' => View::POS_END, 'viewFile' => '_angular']) ?>

<div ng-app="dApp" ng-view=""></div>
