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

// data provider
configProvider = {
    multisort: false,
    sortAttr:{},
};

$scope.provider = {
    maxSize: 5,
    sort: function(attr){
        yii.angular.setSort(attr,configProvider);
        $scope.provider.query();
    },
    isAsc: function(attr){
        return configProvider.sortAttr[attr] == true;
    },
    isDesc: function(attr){
        return configProvider.sortAttr[attr] === false;
    },
    query: function(){
        $scope.rows = <?= $restName;?>.query({
            page: $scope.provider.currentPage,
            sort: yii.angular.getSort(configProvider),
        }, function (r, headerCallback) {
            yii.angular.getPagerInfo($scope.provider, headerCallback);
        });
    }
};

// initial load
$scope.provider.query();

// delete Item
$scope.deleteModel = function(model){
    if(confirm('Are you sure you want to delete')){
<?php if(count($pks) > 1){
    echo "        id = [model.".  implode(', model.', $pks)."].join();\n";
}else{
    echo "        id = model.{$pks[0]};\n";
}?>
        <?= $restName;?>.remove({id:id},{},function(){
            $scope.provider.query();
        });
    }
}