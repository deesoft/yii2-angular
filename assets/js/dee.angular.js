angular.module('dee.angular', [])
    .directive('onLastRepeat', function () {
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
    })
    .directive('sortLink', function () {
        return {
            restrict: 'A',
            scope: {
                provider: '=sortLink',
                cb: '&sortQuery',
            },
            link: function (scope, element,attrs) {
                if (attrs.sortField) {
                    element.on('click', function () {
                        var field = attrs.sortField;
                        scope.provider._sortAttrs = scope.provider._sortAttrs || {};
                        v = scope.provider._sortAttrs[field];
                        if (v === undefined) {
                            scope.provider._sortAttrs[field] = true;
                        } else {
                            delete scope.provider._sortAttrs[field];
                            if (v) {
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
                        
                        if(attrs.sortQuery){
                            query = scope.cb;
                        }else{
                            query = scope.provider.query;
                        }
                        if(query){
                            query();
                        }
                    });
                }
            }
        };
    });
