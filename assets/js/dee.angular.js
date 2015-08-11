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
                            if(sort == ''){
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
                    
                    switch (yii.angular.authMethod){
                        case 'query-param':
                            var qParam = yii.angular.queryParam ? yii.angular.queryParam : 'access-token',param={};
                            param[qParam] = yii.angular.getToken();
                            paramDefaults = angular.extend({}, param, provider.defaults.paramDefaults, paramDefaults);
                            break;
                        case 'http-bearer':
                            
                            break;
                    }

                    actions = angular.extend({}, provider.defaults.actions, actions);
                    for(var i in actions){
                        if(actions[i].url){
                            actions[i].url = yii.angular.applyApiPath(actions[i].url)
                        }
                    }
                    return $resource(path, paramDefaults, actions, options);
                }

                return rest;
            }];
    });
})();
