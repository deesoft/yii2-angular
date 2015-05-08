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

$location = $injector.get('$location');
$routeParams = $injector.get('$routeParams');

$scope.paramId = $routeParams.id;
// model
<?= $restName;?>.get({id:$scope.paramId},function(row){
    $scope.model = row;
});
$scope.errors = {};

// save Item
$scope.save = function(){
    <?= $restName;?>.update({id:$scope.paramId},$scope.model,function(model){
<?php if(count($pks) > 1){
    echo "        id = [model.".  implode(', model.', $pks)."].join();\n";
}else{
    echo "        id = model.{$pks[0]};\n";
}?>
        $location.path('/view/' + id);
    });
}