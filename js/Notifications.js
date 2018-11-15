(function ( $ ) {
	
	
 
    $.fn.Notifications = function(options) {
    	
    	function notify(){
    		
    	}
    	
    	/**
         * Options
         * @type {Object}
         */
        this.opts = $.extend({
            seenUrl: '', // Overwritten by widget
            seenAllUrl: '', // Overwritten by widget
            deleteUrl: '', // Overwritten by widget
            deleteAllUrl: '', // Overwritten by widget
            flashUrl: '',
            pollInterval: 5000,
            pollSeen: false,
            xhrTimeout: 2000,
            delay: 5000,
            theme: null,
            counters: [],
            markAllSeenSelector: null,
            deleteAllSelector: null,
            listSelector: null,
            listItemTemplate:
                '<div class="row">' +
                    '<div class="col-xs-10">' +
                        '<div class="title">{title}</div>' +
                        '<div class="description">{description}</div>' +
                        '<div class="timeago">{timeago}</div>' +
                    '</div>' +
                    '<div class="col-xs-2">' +
                        '<div class="actions pull-right">{seen}{delete}</div>' +
                    '</div>' +
                '</div>',
            listItemBeforeRender: function (elem) {
                return elem;
            }
        }, options);

        
        return this;
    };
 
}( jQuery ));