(function () {
    var module = angular.module('dee.ui', []);

    module.directive('dSort', ['$timeout', function ($timeout) {
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
    
    module.factory('$pageInfo', function () {
        var pub = function (callback, info) {
            var res = {}, p = info || {};
            angular.forEach(pub.pagerHeaderMap, function (val, key) {
                res[key] = p[key] = callback(val);
            });
        };
        
        pub.pagerHeaderMap = {
            totalItems: 'X-Pagination-Total-Count',
            pageCount: 'X-Pagination-Page-Count',
            page: 'X-Pagination-Current-Page',
            itemPerPage: 'X-Pagination-Per-Page',
        };

        return pub;
    });
})();
