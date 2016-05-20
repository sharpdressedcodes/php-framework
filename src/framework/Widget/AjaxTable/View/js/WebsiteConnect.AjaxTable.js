(function(){

    'use strict';

    var AjaxTable = function(options){

        WebsiteConnect.Observable.apply(this, arguments);
        AjaxTable.prototype = WebsiteConnect.extend(AjaxTable.prototype, WebsiteConnect.Observable.prototype);

        this.defaults = {
            tableId: null,
            paginationId: null,
            maxPages: 5
        };

        var opt = WebsiteConnect.extend({}, this.defaults, options);

        for (var option in opt){
            if (opt.hasOwnProperty(option)){
                this[option] = opt[option];
            }
        }

        this.order = null;
        this.sort = null;
        this.table = document.querySelector('#' + this.tableId);
        this.pagination = document.querySelector('#' + this.paginationId);
        this.$paginationElements = $('#' + this.paginationId + ' .pagination li a');
        this.$sortElements = $('#' + this.tableId + ' a.table-sort-url');

        this.ajaxify();

    };
    AjaxTable.prototype.ajaxify = function(){

        var self = this;

        this.$paginationElements.each(each);
        this.$sortElements.each(each);

        function each(index, url){
            var $url = $(url);
            $url.on('click', function(event){
                if ($url.hasClass('table-sort-url')){
                    var href = $url.attr('href');
                    self.order = WebsiteConnect.url.getValue(href, 'order');
                    self.sort = WebsiteConnect.url.getValue(href, 'sort');
                }
                self.onNavigate.call(self, event);
            });
        }

    };
    AjaxTable.prototype.onNavigate = function(event){

        var self = this;
        var href = null;
        var element = event.target;

        event.preventDefault();

        element.tagName.toLowerCase() !== 'a' && (element = element.parentNode);
        href = $(element).attr('href');

        this.trigger('data.receive', {href: href});

        $.ajax({
            url: href,
            dataType: 'json',
            success: function(data, textStatus, jqXHR){
                data.url = href;
                self.update(data, element);
                self.trigger('data.receive.success', data);
            },
            error: function(jqXHR, textStatus, errorThrown){
                self.trigger('data.receive.error', errorThrown);
            }
        });

    };
    AjaxTable.prototype.update = function(data, target){
        //if ({}.toString.call(data) !== '[object string]'){
            this.updatePagination(data);
            this.updateSortButtons(data, target);
            this.updateTable(data);
        //}
    };
    AjaxTable.prototype.updateTable = function(data){

        var $table = $(this.table);

        if (data.userData.length > 0){
            $table.fadeIn();
        } else {
            $table.fadeOut();
        }

    };
    AjaxTable.prototype.updatePagination = function(data){

        var pages = [];
        var parent = null;
        var qs = WebsiteConnect.url.replace(fixUrl(location.search), ['sort', 'order'], [this.sort || data.sort, this.order || data.order]);
        var clone = null;
        var fragment = document.createDocumentFragment();

        // Hide/show pagination.
        if (data.pages > 1){
            $(this.pagination).fadeIn();
        } else {
            $(this.pagination).fadeOut();
            return;
        }

        this.$paginationElements.each(function(index, element){

            var start = 0;
            var $el = $(element);
            var title = $el.attr('aria-label').toLowerCase();

            !parent && (parent = $el.parent().parent()[0]);

            switch(title){
                case 'first':
                case 'previous':
                    start = title === 'first' ? 0 : data.start - data.limit;
                    if (data.current === 1){
                        $el.parent().addClass('disabled');
                    } else {
                        $el.parent().removeClass('disabled');
                    }
                    $el.attr('href', data.current === 1 ? '#' : WebsiteConnect.url.replace(qs, 'start', start));
                    break;

                case 'next':
                case 'last':
                    start = title === 'next' ? data.start + data.limit : (data.pages - 1) * data.limit;
                    if (data.current === data.pages){
                        $el.parent().addClass('disabled');
                    } else {
                        $el.parent().removeClass('disabled');
                    }
                    $el.attr('href', data.current === data.pages ? '#' : WebsiteConnect.url.replace(qs, 'start', start));
                    break;

                default:
                    pages.push(element.parentNode);
            }

        });

        // Clone the first button.
        clone = pages[0].cloneNode(true);
        $(clone).css('display', '');

        var previousActive = 0;

        // Remove old page buttons.
        pages.forEach(function(page){

            var $page = $(page);

            $page.hasClass('active') && (previousActive = (parseInt($page.text()) - 1) * data.limit);
            $page.remove();

        });

        var startIndex = 0;
        var startPage = 0;
        var endPage = 0;
        var lastPossibleStartIndex = data.limit * (data.pages - data.maxPages);

        previousActive > 0 && data.start < previousActive && (startIndex = data.start);

        setLoopVars();

        (endPage - 1) * data.limit <= data.start && (startIndex = data.start);

        setLoopVars();

        function setLoopVars(){

            startIndex > lastPossibleStartIndex && (startIndex = lastPossibleStartIndex);
            startIndex < 0 && (startIndex = 0);

            startPage = (startIndex  / data.limit) + 1;

            endPage = startPage + data.maxPages;
            endPage > data.pages + 1 && (endPage = data.pages + 1);

        }

        // Create new page buttons.
        for (var i = startPage; i < endPage; i++){

            var el = clone.cloneNode(true);
            var $el = $(el);
            var $a = $el.find('a');
            var title = 'Page ' + i;

            $a.attr('href', data.current === i ? '#' : WebsiteConnect.url.replace(qs, 'start', (i - 1) * data.limit));
            $a.attr('title', title);
            $a.attr('aria-label', title);
            $a.text(i);
            $a.on('click', this.onNavigate.bind(this));

            if (data.current === i){
                $el.addClass('active');
            } else {
                $el.removeClass('active');
            }

            fragment.appendChild(el);

        }

        // Insert the new buttons.
        parent.insertBefore(fragment, parent.querySelector('a[title="Next"]').parentNode);

        // Update pagination elements.
        this.$paginationElements = $('#' + this.paginationId + ' .pagination li a');

        function fixUrl(url){
            return WebsiteConnect.url.strip(url, 'reset');
        }

    };
    AjaxTable.prototype.updateSortButtons = function(data, target){

        var self = this;
        var changed = false;
        var order = this.order || data.order;
        var sort = this.sort || data.sort;
        var $sortContainers = $('#' + this.tableId + ' .table-sort-container');

        (!order || ['asc', 'desc'].indexOf(order) === -1 || order === '') && (order = 'asc');
        (!sort || sort === '') && (sort = 'user_id');
        order = order.toLowerCase();
        sort = sort.toLowerCase();

        // Only swapElements if sort || order was clicked.
        target && $(target).hasClass('table-sort-url') && swapElements(target);

        // Update sort elements reference.
        changed && (this.$sortElements = $('#' + this.tableId + ' a.table-sort-url'));

        if (data.userData.length > 1){
            $sortContainers.fadeIn();
        } else {
            $sortContainers.fadeOut();
        }

        function swapElements(target){

            var $target = $(target);
            var $newSpan = $target.find('span').clone();
            var $span = $(self.table).find('span.disabled');
            var $container = $span.parent();
            var $a = $container.find('a');
            var $clone = $a.clone(true);
            var $old = $clone.find('span');
            var href = WebsiteConnect.url.strip($a.attr('href').replace('#', ''), 'reset');
            var hrefOrder = WebsiteConnect.url.getValue(href, 'order');
            var newOrder = hrefOrder === 'asc' ? 'desc' : 'asc';

            $clone.attr('href', WebsiteConnect.url.replace(href, 'order', newOrder));
            $clone.attr('title', $clone.attr('title').replace(order + 'ending', newOrder + 'ending'));
            $span.removeClass('disabled');

            $old.replaceWith($span.clone(true));
            $span.replaceWith($clone);

            $newSpan.addClass('disabled');
            $target.replaceWith($newSpan);

            changed = true;

        }

    };

    WebsiteConnect.widgets.AjaxTable = AjaxTable;

})();