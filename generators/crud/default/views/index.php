<?php

use yii\helpers\Inflector;

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
    echo "                    <th>".Inflector::id2camel($column)."</th>\n";
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
                    <td>{{(pager.currentPage-1)*pager.itemPerPage + no + 1}}</td>
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
        <pagination total-items="pager.totalItems" ng-model="pager.currentPage"
                    max-size="pager.maxSize" items-per-page="pager.itemPerPage"
                    ng-change="query()"
                    class="pagination-sm" boundary-links="true"></pagination>
    </div>
</div>
