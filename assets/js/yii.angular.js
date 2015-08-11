yii.angular = (function ($) {

    var pub = {
        apiPrefix: undefined,
        authMethod: undefined,
        pagerHeaderMap: {
            totalItems: 'X-Pagination-Total-Count',
            pageCount: 'X-Pagination-Page-Count',
            page: 'X-Pagination-Current-Page',
            itemPerPage: 'X-Pagination-Per-Page',
        },
        getPagerInfo: function (info, callback) {
            $.each(pub.pagerHeaderMap, function (key, val) {
                info[key] = callback(val);
            });
        },
        isAbsolutePath: function (path) {
            var RE = new RegExp('^(?:[a-z]+:/)?/', 'i');
            return RE.test(path);
        },
        applyApiPath:function (path){
            if(pub.apiPrefix != undefined && !pub.isAbsolutePath(path)){
                return pub.apiPrefix + path;
            }else{
                return path;
            }
        },
        initProperties:function (props){
            $.each(props,function(key,val){
                pub[key] = val;
            });
        }
    };
    
    return pub;
})(jQuery);