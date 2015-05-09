<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $generator dee\angular\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use dee\angular\Angular;

/* @var $this yii\web\View */
/* @var $angular Angular */

?>

<div class="<?= $generator->controllerID ?>-form">
    <form name="Form" d-errors="errors">
        <div ng-if="errors.status">
            <h1>Error {{errors.status}}: {{errors.text}}</h1>
            <ul>
                <li ng-repeat="(field,msg) in errors.data">{{field}}: {{msg}}</li>
            </ul>
        </div>

<?php foreach ($generator->getColumnNames() as $attribute){
    if (in_array($attribute, $safeAttributes)) {
        $containerCls = 'form-group'.($model->isAttributeRequired($attribute)?' required':'');
        $attrLabel = $model->getAttributeLabel($attribute);
        $attrId = Html::getInputId($model, $attribute);
        echo <<<FIELD
        <div class="{$containerCls}" ng-class="{error:errors.{$attribute}}">
            <label for="{$attrId}" class="control-label">{$attrLabel}</label>
            <input id="{$attrId}" name="{$attribute}" class="form-control" ng-model="model.{$attribute}">
            <div class="help-block">{{errors.{$attribute}}}</div>
        </div>

FIELD;
        
    }
} ?>
        <div class="form-group">
            <button class="btn btn-primary" ng-click="save()">Save</button>
        </div>
    </form>
</div>