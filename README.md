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
            'controller'=>'IndexCtrl', // optional
        ],
        '/view/:id'=>[
            'view'=>'view', // if controller empty, controller will be as ViewCtrl
            'di'=>['$location', '$routeParams'], // $scope and $injector are always be added              
        ],
        '/edit/:id'=>[
            'view'=>'edit',
        ],
        '/create'=>[
            'view'=>'create',
        ],
    ]
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
    <li ng-repeat="item in items">
        <a ng-href="/view/{{item.id}}">{{item.name}}</a>
    </li>
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

```

Use GII to generate crud
------------------------
Install [deesoft/yii2-gii](https://github.com/deesoft/yii2-gii) then add config
```php
...
if (!YII_ENV_TEST) {
//     configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'angular' => ['class' => 'dee\gii\generators\angular\Generator'],
        ]
    ];
}

```