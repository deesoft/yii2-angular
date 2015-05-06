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
        },
        getSort: function(config){
            if(Object.keys(config.sortAttr).length){
                var sort = [];
                $.each(config.sortAttr,function (key,val){
                    sort.push((val?'':'-')+key);
                });
                return sort.reverse().join();
            }
        },
        setSort: function (attr,config){
            v = config.sortAttr[attr];
            if(config.multisort){
                if(v === undefined){
                    config.sortAttr[attr] = true;
                }else{
                    delete config.sortAttr[attr];
                    if(v){
                        config.sortAttr[attr] = false;
                    }
                }
            }else{
                if(v === undefined){
                    config.sortAttr = {attr:true};
                }else{
                    config.sortAttr = {};
                    if(v){
                        config.sortAttr[attr] = false;
                    }
                }
            }
        },
    };
    return pub;
})(jQuery);