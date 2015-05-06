<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator dee\angular\generators\crud\Generator */

$class = $generator->modelClass;
$pks = $class::primaryKey();

$restName = StringHelper::basename($generator->modelClass);
?>

// pagination
$scope.pager = {maxSize: 10};
$scope.query = function () {
    $scope.rows = <?= $restName;?>.query({
        page: $scope.pager.currentPage,
    }, function (r, headerCallback) {
        yii.angular.getPagerInfo($scope.pager, headerCallback);
    });
}

// initial load
$scope.query();

// delete Item
$scope.deleteModel = function(model){
    if(confirm('Are you sure you want to delete')){
<?php if(count($pks) > 1){
    echo "        id = [model.".  implode(', model.', $pks)."].join();\n";
}else{
    echo "        id = model.{$pks[0]};\n";
}?>
        <?= $restName;?>.remove({id:id},{},function(){
            $scope.query();
        });
    }
}