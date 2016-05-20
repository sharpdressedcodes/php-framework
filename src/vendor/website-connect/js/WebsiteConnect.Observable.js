(function(){

    'use strict';

    WebsiteConnect.Observable = function(){
        this.listeners = {};
        this.contexts = {};
    };
    WebsiteConnect.Observable.prototype.on = function(event, callback, context){

        var self = this;

        if (typeof event === 'string'){
            _addListener(event, callback, context);
        } else {
            for (var i = 0, i_ = event.length; i < i_; i++){
                _addListener(event[i], callback, context);
            }
        }

        function _addListener(event, callback, context){

            if (typeof self.listeners[event] === 'undefined'){
                self.listeners[event] = [];
                self.contexts[event] = [];
            }

            self.listeners[event].push(callback);
            self.contexts[event].push(context || self);

        }

    };
    WebsiteConnect.Observable.prototype.off = function(event, callback){

        var self = this;

        if (typeof event === 'string'){
            _removeListener(event, callback);
        } else {
            for (var i = 0, i_ = event.length; i < i_; i++){
                _removeListener(event[i], callback);
            }
        }

        function _removeListener(event, callback){

            if (self.listeners.hasOwnProperty(event)){
                for (var i = 0, i_ = self.listeners[event].length; i < i_; i++){
                    if (self.listeners[event][i] === callback){
                        self.listeners[event].splice(i, 1);
                        self.contexts[event].splice(i, 1);
                        if (self.listeners[event].length === 0){
                            delete self.listeners[event];
                            delete self.contexts[event];
                        }
                        break;
                    }
                }
            }

        }

    };
    WebsiteConnect.Observable.prototype.clearListeners = function(event){

        var self = this;

        switch (typeof event){

            case 'undefined':
                this.listeners = [];
                this.contexts = [];
                break;

            case 'string':
                _clearListeners(event);
                break;

            case 'object':
                if ({}.toString.call(event) === '[object Array]'){
                    for (var i = 0, i_ = event.length; i < i_; i++){
                        _clearListeners(event[i].trim());
                    }
                }
                break;

            default:
        }

        function _clearListeners(event){

            if (self.listeners.hasOwnProperty(event)){
                delete self.listeners[event];
                delete self.contexts[event];
            }

        }

    };
    WebsiteConnect.Observable.prototype.trigger = function(event, data){

        if (this.listeners.hasOwnProperty(event)){
            for (var i = 0, i_ = this.listeners[event].length; i < i_; i++){
                if (this.listeners[event][i].call(this.contexts[event][i], data) === false){
                    // Break the event chain.
                    return false;
                }
            }
        }

        return true;

    };
    WebsiteConnect.Observable.prototype.hasListeners = function(){
        return Object.getOwnPropertyNames(this.listeners).length > 0;
    };

})();