(function () {
    dee = angular.module('dee.angular', ['ngResource']);

    dee.directive('dSort', ['$timeout', function ($timeout) {
            return {
                restrict: 'AE',
                require: '?ngModel',
                link: function (scope, element, attrs, ngModel) {
                    if (!ngModel)
                        return;

                    var multiple = angular.isDefined(attrs.multisort) ? scope.$parent.$eval(attrs.multisort) : false;

                    $timeout(function () {
                        element.on('click', '[sort-field]', function () {
                            $(this).removeClass('asc desc');
                            var sort = ngModel.$modelValue;
                            var field = $(this).attr('sort-field');
                            if (multiple) {
                                if (sort != '' && sort != undefined) {
                                    sort = sort.split(',');
                                    var _sort = [];
                                    var add = 1;
                                    for (var i in sort) {
                                        if (sort[i].charAt(0) == '-' && sort[i].substr(1) == field) {
                                            add = 0;
                                        } else if (sort[i] == field) {
                                            add = -1;
                                        } else {
                                            _sort.push(sort[i]);
                                        }
                                    }
                                    if (add == 1) {
                                        _sort.unshift(field);
                                        $(this).addClass('asc');
                                    } else if (add == -1) {
                                        _sort.unshift('-' + field);
                                        $(this).addClass('desc');
                                    }
                                    sort = _sort.join(',');
                                } else {
                                    sort = field;
                                }
                            } else {
                                element.find('[sort-field]').removeClass('asc desc');
                                if (sort == field) {
                                    sort = '-' + field;
                                    $(this).addClass('desc');
                                } else if (sort == '-' + field) {
                                    sort = '';
                                } else {
                                    sort = field;
                                    $(this).addClass('desc');
                                }
                            }
                            if (sort == '') {
                                sort = undefined;
                            }
                            ngModel.$setViewValue(sort);
                        });
                    });
                }
            };
        }]);

    dee.provider('DRest', function () {
        var provider = this;

        this.defaults = {
            // Default actions configuration
            actions: {
                update: {method: 'PUT'},
                patch: {method: 'PATCH'},
            },
            paramDefaults: {}
        };


        this.$get = ['$resource', function ($resource) {

                function rest(path, paramDefaults, actions, options) {
                    path = yii.angular.applyApiPath(path);

                    actions = angular.extend({}, provider.defaults.actions, actions);
                    for (var i in actions) {
                        if (actions[i].url) {
                            actions[i].url = yii.angular.applyApiPath(actions[i].url)
                        }
                    }
                    return $resource(path, paramDefaults, actions, options);
                }

                return rest;
            }];
    });

    dee.config(['$httpProvider', function ($httpProvider) {
            $httpProvider.interceptors.push('authInterceptor');
        }
    ]);

    dee.factory('authInterceptor', ['$q', '$location', function ($q, $location) {
            return {
                request: function (config) {
                    var token = yii.angular.getToken();
                    switch (yii.angular.authMethod) {
                        case 'query-param':
                            if (config.params) {
                                config.params['access-token'] = token;
                            } else {
                                config.params = {'access-token': token};
                            }
                            break;

                        case 'http-bearer':
                            config.headers.Authorization = 'Bearer ' + token;
                            break;

                        default :
                    }
                    return config;
                },
                responseError: function (rejection) {
                    if (rejection.status == 401 && yii.angular.loginUrl != undefined) {
                        $location.path(yii.angular.loginUrl).replace();
                    }
                    return $q.reject(rejection);
                }
            };
        }]);
})();
