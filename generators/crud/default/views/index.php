<?php

use yii\helpers\Inflector;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $generator dee\angular\generators\crud\Generator */

$maxColumn = 6;
echo "<?php\n";
?>
use dee\angular\Angular;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $angular Angular */

Angular::renderScript('_index.js');
?>

<div class="<?= $generator->controllerID ?>-index">
    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
    <p>
        <?= "<?= " ?>Html::a('Create', '#/create', ['class' => 'btn btn-success']) ?>
    </p>
    <div class="grid-view">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
<?php 
$count = 0;
foreach ($generator->getColumnNames() as $column){
    $count++;
    if($count == $maxColumn){
        echo "<!--\n";
    }
    $header = Inflector::id2camel($column);
    echo <<<HEADER
                    <th><a href="javascript:;" ng-click="provider.sort('$column')"
                        ng-class="{asc:provider.isAsc('$column'),desc:provider.isDesc('$column')}">
                        {$header}</a></th>

HEADER;
}
if($count >= $maxColumn){
    echo "-->\n";
}
?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="(no,model) in rows">
                    <td>{{(provider.currentPage-1)*provider.itemPerPage + no + 1}}</td>
<?php
$count = 0;
foreach ($generator->getColumnNames() as $column){
    $count++;
    if($count == $maxColumn){
        echo "<!--\n";
    }
    echo "                    <td>{{model.{$column}}}</td>\n";
}
if($count >= $maxColumn){
    echo "-->\n";
}
?>
                    <td>
                        <a ng-href="#/view/{{model.id}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <a ng-href="#/update/{{model.id}}"><span class="glyphicon glyphicon-pencil"></span></a>
                        <a href="javascript:;" ng-click="deleteModel(model)"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination total-items="provider.totalItems" ng-model="provider.currentPage"
                    max-size="provider.maxSize" items-per-page="provider.itemPerPage"
                    ng-change="provider.query()"
                    class="pagination-sm" boundary-links="true"></pagination>
    </div>
</div>
