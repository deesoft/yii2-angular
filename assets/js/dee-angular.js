(function () {
    var module = angular.module('dee', ['dee.ui', 'dee.rest']);

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