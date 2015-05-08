(function () {
    dee = angular.module('dee.angular', []);
    dee.directive('onLastRepeat', function () {
        return {
            restrict: 'A',
            scope: {
                cb: '&onLastRepeat',
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

    dee.directive('sortProvider', function () {
        return {
            restrict: 'A',
            scope: {
                provider: '=sortProvider',
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
})();
