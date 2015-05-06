<?php

/* @var $this yii\web\View */
/* @var $generator dee\angular\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();

echo "<?php\n";
?>
use dee\angular\Angular;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $angular Angular */

Angular::renderScript('_view.js');
?>

<div class="<?= $generator->controllerID ?>-view">
    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <a ng-href="#/update/{{paramId}}" class="btn btn-primary">Update</a>
        <a href="javascript:;" ng-click="deleteModel()"class="btn btn-danger">Delete</a>
    </p>

    <table class="table table-striped table-bordered detail-view">
<?php foreach ($generator->getColumnNames() as $attribute){
    $label = $model->getAttributeLabel($attribute);
    echo "        <tr><th>{$label}</th><td>{{model.$attribute}}</td></tr>";
}?>
    </table>
</div>