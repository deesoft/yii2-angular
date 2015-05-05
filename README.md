yii2-angular
============

Yii2 angular extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require deesoft/yii2-angular "~1.0"
```

or add

```
"deesoft/yii2-angular": "~1.0"
```

to the require section of your `composer.json` file.

Usage
-----

In view file
```php
<?php
use dee\angular\Angular;

/* @var $this yii\web\View */
?>

<?= Angular::widget([
    'name' => 'myapp', // default dApp
    'routes'=>[
        '/'=>[
            'view'=>'index',
            'controller'=>'IndexCtrl',
        ],
        '/view/:id'=>[
            'view'=>'view',
            'controller'=>'ViewCtrl',
            'di'=>[], // like => ['$location','$route'],
        ],
        '/edit/:id'=>[
            'view'=>'edit',
            'controller'=>'EditCtrl',
            'di'=>[],
        ],
        '/create'=>[
            'view'=>'create',
            'controller'=>'CreateCtrl',
            'di'=>[],
        ],
    ],
    'jsFile' => 'script.js' // optional
])?>
```
Then `index.php`
```php
<?php

use dee\angular\Angular;

/* @var $this yii\web\View */
/* @var $angular Angular */
?>

<ul>
    <li ng-repeat="item in items"><a ng-href="/view/{{item.id}}">{{item.name}}</a></li>
</ul>

<?php Angular::beginScript() ?>
// this script will be inserted to IndexCtrl
Rest = $injector.get('Rest');
$scope.items = Rest.all();
$scope.deleteItem = function (id) {
    Rest.remove(id);
    $scope.items = Rest.all();
}
<?php Angular::endScript(); ?>

``