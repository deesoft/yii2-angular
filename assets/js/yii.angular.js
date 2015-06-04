yii.angular = (function ($) {

    var pub = {
        pagerHeaderMap: {
            totalItems: 'X-Pagination-Total-Count',
            pageCount: 'X-Pagination-Page-Count',
            currentPage: 'X-Pagination-Current-Page',
            itemPerPage: 'X-Pagination-Per-Page',
        },
        getPagerInfo: function (info, callback) {
            $.each(pub.pagerHeaderMap, function (key, val) {
                info[key] = callback(val);
            });
        }
    };
    return pub;
})(jQuery);