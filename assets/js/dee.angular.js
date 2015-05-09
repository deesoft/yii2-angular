(function () {
    dee = angular.module('dee.angular', []);
    dee.directive('dLastRepeat', function () {
        return {
            restrict: 'A',
            scope: {
                cb: '&dLastRepeat',
            },
            link: function (scope, element) {
                if (scope.$parent.$last) {
                    setTimeout(function () {
                        scope.cb(element);
                    }, 0);
                }
            }
        };
    });

    dee.directive('dSortProvider', function () {
        return {
            restrict: 'A',
            scope: {
                provider: '=dSortProvider',
                cb: '&sortQuery',
            },
            link: function (scope, element, attrs) {
                if (attrs.sortField) {
                    element.on('click', function () {
                        var field = attrs.sortField;
                        var multisort = scope.provider.multisort;
                        scope.provider._sortAttrs = scope.provider._sortAttrs || {};

                        v = scope.provider._sortAttrs[field];
                        if (multisort) {
                            if (v === undefined) {
                                scope.provider._sortAttrs[field] = true;
                            } else {
                                delete scope.provider._sortAttrs[field];
                                if (v) {
                                    scope.provider._sortAttrs[field] = false;
                                }
                            }
                        } else {
                            scope.provider._sortAttrs = {};
                            if (v === undefined) {
                                scope.provider._sortAttrs[field] = true;
                            } else if (v) {
                                scope.provider._sortAttrs[field] = false;
                            }
                        }
                        if (Object.keys(scope.provider._sortAttrs).length) {
                            var sort = [];
                            $.each(scope.provider._sortAttrs, function (key, val) {
                                sort.push((val ? '' : '-') + key);
                            });
                            scope.provider.sort = sort.reverse().join();
                        } else {
                            scope.provider.sort = undefined;
                        }
                        // change css class
                        element.removeClass('asc desc');
                        if (scope.provider._sortAttrs[field] !== undefined) {
                            element.addClass(scope.provider._sortAttrs[field] ? 'asc' : 'desc');
                        }

                        // execute query
                        query = attrs.sortQuery ? scope.cb : scope.provider.query;

                        if (query) {
                            query();
                        }
                    });
                }
            }
        };
    });

    dee.directive('dErrors', function () {
        return {
            restrict: 'A',
            scope: {
                errors: '=dErrors',
            },
            link: function (scope, element) {
                element
                    .off('keypress.validation', ':input[ng-model]')
                    .on('keypress.validation', ':input[ng-model]', function () {
                        if (scope.errors.status) {
                            delete scope.errors.status;
                            delete scope.errors.text;
                        }
                        var attr = $(this).attr('ng-model').substring(6);
                        if (scope.errors.data[attr]) {
                            delete scope.errors.data[attr];
                        }
                    });
            }
        };
    });
})();
