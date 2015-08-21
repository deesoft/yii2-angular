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
use dee\angular\NgView;

/* @var $this yii\web\View */
?>

<?= NgView::widget([
    'name' => 'myapp', // default dApp
    'routes'=>[
        '/'=>[
            'view' => 'index',
            'js' => 'index.js',
        ],
        '/view/:id'=>[
            'view'=>'view', 
            'js'=>'view.js',
            'injection'=>['$location', '$routeParams'], // $scope and $injector are always be added              
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

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>

<ul>
    <li ng-repeat="item in items">
        <a ng-href="/view/{{item.id}}">{{item.name}}</a>
    </li>
</ul>
```

`index.js`
```javascript
Rest = $injector.get('Rest');

query = function(){
    Rest.query({},function(r){
        $scope.items = r;
    });
}

$scope.deleteItem = function (id) {
    Rest.remove({id:id},{},function(){
        query();
    });
}
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