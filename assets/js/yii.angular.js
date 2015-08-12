yii.angular = (function ($) {
    var TOKEN_KEY = '_d426_angular_token';
    var pub = {
        apiPrefix: undefined,
        authMethod: undefined,
        loginUrl: undefined,
        tokenDuration: 24 * 3600,
        renewAuth: true,
        pagerHeaderMap: {
            totalItems: 'X-Pagination-Total-Count',
            pageCount: 'X-Pagination-Page-Count',
            page: 'X-Pagination-Current-Page',
            itemPerPage: 'X-Pagination-Per-Page',
        },
        getPageInfo: function (info, callback) {
            $.each(pub.pagerHeaderMap, function (key, val) {
                info[key] = callback(val);
            });
        },
        isAbsolutePath: function (path) {
            var RE = new RegExp('^(?:[a-z]+:/)?/', 'i');
            return RE.test(path);
        },
        applyApiPath: function (path) {
            if (pub.apiPrefix != undefined && !pub.isAbsolutePath(path)) {
                return pub.apiPrefix + path;
            } else {
                return path;
            }
        },
        getToken:function (){
            var s = localStorage.getItem(TOKEN_KEY);
            if(s){
                var obj = JSON.parse(s);
                if(obj.time > (new Date()).getTime()){
                    if(pub.renewAuth){
                        pub.setToken(obj.token);
                    }
                    return obj.token;
                }
            }
            return undefined;
        },
        setToken:function (token){
            localStorage.setItem(TOKEN_KEY,JSON.stringify({
                time: (new Date()).getTime() + 1000 * pub.tokenDuration,
                token: token
            }));
        },
        initProperties: function (props) {
            $.each(props, function (key, val) {
                pub[key] = val;
            });
        }
    };

    return pub;
})(jQuery);