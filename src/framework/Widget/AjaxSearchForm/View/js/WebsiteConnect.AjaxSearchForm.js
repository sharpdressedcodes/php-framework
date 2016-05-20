(function(){

    'use strict';

    var AjaxSearchForm = function(options){

        WebsiteConnect.Observable.apply(this, arguments);
        AjaxSearchForm.prototype = WebsiteConnect.extend(AjaxSearchForm.prototype, WebsiteConnect.Observable.prototype);

        this.defaults = {
            formId: null,
            closeId: null,
            queryMax: 256,
            queryRegEx: '^[\\w \\-]{1,256}$',
            queryRegExMods: 'i',
            csrfName: 'csrf'
        };

        var opt = WebsiteConnect.extend({}, this.defaults, options);

        for (var option in opt){
            if (opt.hasOwnProperty(option)){
                this[option] = opt[option];
            }
        }

        this.rx = new RegExp(this.queryRegEx, this.queryRegExMods);
        this.form = document.querySelector('#' + this.formId);
        this.closeContainer = document.querySelector('#' + this.closeId);

        this.ajaxify();

    };
    AjaxSearchForm.prototype.ajaxify = function(){

        var self = this;
        var $form = $(this.form);
        var $a = $(this.closeContainer).find('a');

        $a.on('click', function(event){

            var element = event.target;
            var href = $(element).attr('href');

            event.preventDefault();

            self.trigger('data.receive', {href: href});

            $.ajax({
                url: href,
                dataType: 'json',
                success: function(data, textStatus, jqXHR){
                    data.url = href;
                    self.update(data);
                    self.trigger('data.receive.success', data);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    self.trigger('data.receive.error', errorThrown);
                }
            });

        });

        $form.on('submit', function(event){

            event.preventDefault();

            var $search = $form.find('input[type="search"]');
            var query = $search.val();
            var href = $form.attr('action');

            // Validation.
            if (self.rx.test(query)){

                self.trigger('data.receive', {href: href});

                var postData = {query: query};
                postData[self.csrfName] = $form.find('input[type="hidden"]').val();

                $.ajax({
                    url: href,
                    dataType: 'json',
                    method: 'post',
                    data: postData,
                    success: function(data, textStatus, jqXHR){
                        self.update(data);
                        self.trigger('data.receive.success', data);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        self.trigger('data.receive.error', errorThrown);
                    }
                });
            } else {
                self.trigger('query.validation.error', 'Invalid query.');
            }

        });

    };
    AjaxSearchForm.prototype.update = function(data){
        //if (typeof data.error !== 'undefined'){
            this.updateCsrf(data.csrf);
            this.updateCloseContainer(data);
        //}
    };
    AjaxSearchForm.prototype.updateCsrf = function(csrf){
        $(this.form).find('input[type="hidden"]').val(csrf);
    };
    AjaxSearchForm.prototype.updateCloseContainer = function(data){

        var $container = $(this.closeContainer);

        if (data.query && data.query !== ''){
            updateUrl();
            $container.fadeIn();
        } else {
            $container.fadeOut();
            $(this.form).find('input[type="search"]').val('');
        }

        function updateUrl(){

            var $a = $container.find('a');
            var href = $a.attr('href');

            href = WebsiteConnect.url.replace(href, ['order', 'sort'], [data.order, data.sort]);

            $a.attr('href', href);

        }

    };

    WebsiteConnect.widgets.AjaxSearchForm = AjaxSearchForm;

})();