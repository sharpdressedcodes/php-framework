
(function(){

    'use strict';

    var AjaxLoader = function(options){

        WebsiteConnect.Observable.apply(this, arguments);
        AjaxLoader.prototype = WebsiteConnect.extend(AjaxLoader.prototype, WebsiteConnect.Observable.prototype);

        this.defaults = {
            id: null,
            className: 'ajax-loader',
            condition: function(){return true;}
        };

        var opt = WebsiteConnect.extend({}, this.defaults, options);

        for (var option in opt){
            if (opt.hasOwnProperty(option)){
                this[option] = opt[option];
            }
        }

        this.svg = document.querySelector('#' + this.id);

        this.ajaxify();

    };
    AjaxLoader.prototype.ajaxify = function(){

        var self = this;

        $(document).ajaxSend(function(event, request, settings) {
            self.condition(settings) && self.show(settings);
        });

        $(document).ajaxComplete(function(event, request, settings) {
            self.condition(settings) && self.hide(settings);
        });

    };
    AjaxLoader.prototype.show = function(settings, callback){

        var self = this;

        this.trigger('show.before', {condition: this.condition, settings: settings});

        $(this.svg).fadeIn(function(){
            self.trigger('show.after', {condition: self.condition, settings: settings});
            callback && callback();
        });
    };
    AjaxLoader.prototype.hide = function(settings, callback){

        var self = this;

        this.trigger('hide.before', {condition: this.condition, settings: settings});

        $(this.svg).fadeOut(function(){
            self.trigger('hide.after', {condition: self.condition, settings: settings});
            callback && callback();
        });
    };

    WebsiteConnect.widgets.AjaxLoader = AjaxLoader;

})();