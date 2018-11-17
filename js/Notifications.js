//(function ( $ ) {

	var Notifications = (function(options) {
    	
//		function notify(){ //This syntax makes the function callable only from within the class.
    	this.notify = function(notification){ //This syntax makes the function callable by the object (this)
    		toastr.options.closeButton = true;
    		toastr.options.timeOut = this.opts.delay; // How long the toast will display without user interaction
    		toastr.options.extendedTimeOut = this.opts.delay; // How long the toast will display after a user hovers over it
    		
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
    		flash(notification);
    		
    		// Define a callback for when the toast is shown/hidden/clicked
    		toastr.options.onShown = function() {
//    			console.log('hello');
    		}
//    		toastr.options.onHidden = function() { console.log('goodbye'); }
    		toastr.options.onclick = function() { 
    			console.log('clicked');
    			if(notification.url != null && notification.url != ""){
    				window.location = notification.url;
    			}
    			//mark as read
    			markAsRead(notification.id);
    		}
    		toastr.options.onCloseClick = function() { 
    			console.log('close button clicked');
    			//mark as read
    			markAsRead(notification.id);
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
                '<div class="notificationRow" id="notification_{id}">' +
                    '<div class="col-xs-10">' +
                        '<div class="title">{title}</div>' +
                        '<div class="body">{body}</div>' +
                        
                    '</div>' +
                    '<div class="col-xs-2">' +
                        '<div class="actions pull-right">{read}{delete}</div>' +
                        '<div class="timeago pull-right">{timeago}</div>' +
                    '</div>' +
                    '<div class="clearfix"></div>' + 
                    '<hr/>' +
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
        			var rows = "";
        			
        			// Update all counters
                for (var i = 0; i < opts.counters.length; i++) {
                    if ($(opts.counters[i]).text() != notifications.length) {
                        $(opts.counters[i]).text(notifications.length);
                    }
                }
        			
        			for(i in notifications){
        				var notification = notifications[i];
        				if(notification.flashed == 0){
        					notify(notification);
        				}
        				rows += renderRow(notification);
        			}
        			if(opts.listSelector != null && opts.listSelector != ""){
        				$(opts.listSelector).empty().append(rows);
        			}
        		});
        }
        
        this.markAsRead = function(id){
        		console.log(id);
        		$.ajax({
        			url: this.opts.markAsReadUrl,
        			method: "GET",
        			data: {id:id},
        			dataType: "json"
        		})
        		.complete(function(json){
        			if($("#notification_"+id).length){
        				$("#notification_"+id).slideUp();
        			}
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
        
        this.renderRow = function(notification){
        		var html = "";
        		if(notification.url != null && notification.url != ""){
        			html += "<div onclick='window.location=\"" + notification.url + "\"'>";
        		}else{
        			html += "<div>";
        		}
        		
        		html += self.opts.listItemTemplate; 
        		html += "</div>";
        		html = html.replace(/\{id}/g, notification.id);
        		html = html.replace(/\{title}/g, notification.title);
        		html = html.replace(/\{body}/g, notification.body);
        		html = html.replace(/\{read}/g, '<span onclick="markAsRead(' + notification.id + ');" class="notification-seen glyphicon glyphicon-ok" data-keepOpenOnClick></span>');
            html = html.replace(/\{delete}/g, '<span onclick="" class="notification-delete glyphicon glyphicon-remove" data-keepOpenOnClick></span>');
//            html = html.replace(/\{timeago}/g, '<span class="notification-timeago">' + notification.timeago +'</span>');
            
            return html;
        }
        
//        notify();
        
        $(document).ready(function(){
        		$(document).delegate("ul.dropdown-menu [data-keepOpenOnClick]", "click", function(e) {
        			e.stopPropagation();
        		});
        });
        
        return this;
    });
 
//}( jQuery ));