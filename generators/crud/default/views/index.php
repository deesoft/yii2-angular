<?= '<?php' ?>

use yii\web\View;
use dee\angular\JsBlock;
use dee\angular\AngularAsset;
use dee\angular\AngularBootstrapAsset;
use dee\angular\AngucompleteAsset;

/* @var $this yii\web\View */
AngularAsset::register($this);
AngularBootstrapAsset::register($this);
AngucompleteAsset::register($this);
?>
<?= '<?php'?> JsBlock::widget(['pos' => View::POS_END, 'viewFile' => '_angular']) ?>

<div ng-app="dApp" ng-view=""></div>
