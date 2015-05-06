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
    });
