(function(){

    'use strict';

    var WebsiteConnect = {};
    WebsiteConnect.widgets = {};

    WebsiteConnect.extend = function(){

        for (var i = 1, i_ = arguments.length; i < i_; i++) {
            for (var key in arguments[i]) {
                if (arguments[i].hasOwnProperty(key)) {
                    arguments[0][key] = arguments[i][key];
                }
            }
        }

        return arguments[0];

    };

    WebsiteConnect.url = {
        strip: function(url, params){

            !Array.isArray(params) && (params = [params]);

            for (var i = 0, i_ = params.length; i < i_; i++){

                var rx = new RegExp('(\\?|&)(' + params[i] + '=)([^&#]*)((&).*?){0,}', 'i');

                url = url.replace(rx, function (match, p1, p2, p3, p4, p5, offset, string){
                    return typeof p4 !== 'undefined' ? p1: '';
                });

            }

            return url;


        },
        replace: function(url, params, values){

            !Array.isArray(params) && (params = [params]);
            !Array.isArray(values) && (values = [values]);

            for (var i = 0, i_ = params.length; i < i_; i++){

                var rx = new RegExp('(\\?|&)(' + params[i] + '=)([^&#]*)', 'i');

                url = url.replace(rx, (function(index){
                    return function (match, p1, p2, p3, offset, string){
                        return p1 + p2 + values[index];
                    };
                })(i));

            }

            return url;

        },
        getValue: function(url, param){

            var value = null;
            var rx = new RegExp('(\\?|&)(' + param + '=)([^&#]*)', 'i');
            var match = url.match(rx);

            match.length > 2 && (value = match[3]);

            return value;

        }
    };

    window.WebsiteConnect = WebsiteConnect;

})();