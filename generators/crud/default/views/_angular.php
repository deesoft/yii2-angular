<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
?>
<script>

    angular.module('dApp', [
        'ngRoute',
        'ngResource',
        'ui.bootstrap',
        'mdm.angular',
    ])
        .factory('Resource', ['$resource',
            function ($resource) {
                return $resource('<?= Url::to(['resource']) ?>', {}, {
                    query: {
                        params: {'per-page': 10, },
                        isArray: true,
                    },
                    save: {
                        headers: {'X-CSRF-Token': yii.getCsrfToken()},
                    },
                    update: {
                        method: 'PUT',
                        headers: {'X-CSRF-Token': yii.getCsrfToken()},
                    },
                });
            }])
        .config(['$routeProvider',
            function ($routeProvider) {
                $routeProvider.
                    when('/view/:id', {
                        templateUrl: '<?= Url::to(['template', 'view' => 'view']) ?>',
                        controller: 'ViewCtrl',
                        name: 'view',
                    }).
                    when('/edit/:id', {
                        templateUrl: '<?= Url::to(['template', 'view' => 'edit']) ?>',
                        controller: 'EditCtrl',
                        name: 'edit',
                    }).
                    when('/create', {
                        templateUrl: '<?= Url::to(['template', 'view' => 'create']) ?>',
                        controller: 'CreateCtrl',
                        name: 'create',
                    }).
                    when('/list', {
                        templateUrl: '<?= Url::to(['template', 'view' => 'list']) ?>',
                        controller: 'ListCtrl',
                        name: 'list',
                    }).
                    otherwise({
                        redirectTo: '/list',
                    });
            }])
        .controller('ListCtrl', ['$scope', 'Resource',
            function ($scope, Resource) {
                var headerPageMap = {
                    totalItems: 'X-Pagination-Total-Count',
                    pageCount: 'X-Pagination-Page-Count',
                    currentPage: 'X-Pagination-Current-Page',
                    itemPerPage: 'X-Pagination-Per-Page',
                };

                $scope.pager = {maxSize: 5};

                var gotoPage = function (page) {
                    $scope.rows = Resource.query({
                        page: page,
                    }, function (r, headers) {
                        angular.forEach(headerPageMap, function (val, key) {
                            $scope.pager[key] = headers(val);
                        });
                    });
                }

                $scope.pageChange = function () {
                    gotoPage($scope.pager.currentPage);
                }

                gotoPage();
            }])
        .controller('ViewCtrl', ['$scope', 'Resource', '$routeParams',
            function ($scope, Resource, $routeParams) {
                $scope.model = Resource.get({
                    id: $routeParams.id,
                });
            }])
        .controller('CreateCtrl', ['$scope', 'Resource',
            function ($scope, Resource) {
                $scope.model = new Resource();

                $scope.save = function () {
                    $scope.model.$save({}, function (model) {
                        window.location.hash = '#/view/' + model.id;
                    }, function (error) {

                    });
                }
            }]);
        .controller('EditCtrl', ['$scope', 'Resource', '$routeParams',
            function ($scope, Resource, $routeParams) {
                $scope.model = new Resource.get({id:$routeParams.id});

                $scope.save = function () {
                    $scope.model.$save({}, function (model) {
                        window.location.hash = '#/view/' + model.id;
                    }, function (error) {

                    });
                }
            }]);
</script>