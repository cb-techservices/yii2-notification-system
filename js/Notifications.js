//(function ( $ ) {

	var Notifications = (function(options) {
    	
//		function notify(){ //This syntax makes the function callable only from within the class.
    	this.notify = function(notification){ //This syntax makes the function callable by the object (this)
    		toastr.options.closeButton = true;
//    		toastr.options.timeOut = this.opts.delay; // How long the toast will display without user interaction
//    		toastr.options.extendedTimeOut = 30; // How long the toast will display after a user hovers over it
    		toastr.options.timeOut = 0;
    		toastr.options.extendedTimeOut = 0;
    		
    		if(notification.type == 'default'){
    			toastr.info(notification.body, notification.title);
    		}else if(notification.type == 'success'){
    			toastr.success(notification.body, notification.title);
    		}else if(notification.type == 'warning'){
    			toastr.warning(notification.body, notification.title);
    		}else if(notification.type == 'error'){
    			toastr.error(notification.body, notification.title);
    		}else{
    			toastr.info(notification.body, notification.title);
    		}
    		
    		//Mark as flashed since we're showing the notification with toastr.
//    		flash(notification);
    		
    		// Define a callback for when the toast is shown/hidden/clicked
//    		toastr.options.onShown = function() { console.log('hello'); }
//    		toastr.options.onHidden = function() { console.log('goodbye'); }
    		toastr.options.onclick = function() { 
    			console.log('clicked');
    			if(notification.url != null && notification.url != ""){
    				window.location = notification.url;
    			}
    			//mark as read
    			markAsRead(notification);
    		}
    		toastr.options.onCloseClick = function() { 
    			console.log('close button clicked');
    			//mark as read
    			markAsRead(notification);
    		}
    	}
    	
    	/**
         * Options
         * @type {Object}
         */
        this.opts = $.extend({
            pollUrl: '', // Overwritten by widget
            markAsReadUrl: '', // Overwritten by widget
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
        
        this.poll = function(){
        		$.ajax({
        			url: this.opts.pollUrl,
        			method: "GET",
        			data: {read:0},
        			dataType: "json",
//        			complete: setTimeout(function() {
//        				self.poll(this.opts)
//                }, this.opts.pollInterval),
//                timeout: opts.xhrTimeout
        		})
        		.complete(function(json){
        			console.log(json.responseJSON);
        			var notifications = json.responseJSON;
        			for(i in notifications){
        				var notification = notifications[i];
        				if(notification.flashed == 0){
        					notify(notification);
        				}
        			}
        		});
        }
        
        this.markAsRead = function(notification){
        		console.log(notification);
        		$.ajax({
        			url: this.opts.markAsReadUrl,
        			method: "GET",
        			data: {id:notification.id},
        			dataType: "json"
        		})
        		.complete(function(json){
        			
        		});
        }
        
        this.markAsUnread = function(){
        	
        }
        
        this.markAllAsRead = function(){
        	
        }
        
        this.markAllAsUnread = function(){
        	
        }
        
        this.flash = function(notification){
	        	$.ajax({
	    			url: this.opts.flashUrl,
	    			method: "GET",
	    			data: {id:notification.id},
	    			dataType: "json"
	    		})
	    		.complete(function(json){
	    			
	    		});
        }
        
//        notify();
        
        return this;
    });
 
//}( jQuery ));