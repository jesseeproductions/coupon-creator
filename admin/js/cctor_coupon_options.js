/*
* WP Color Picker
* since 1.70
*/
jQuery(document).ready(function ($) {    
    $('.color-picker').wpColorPicker();
});

/*
* Color Box Init for Help Videos
* since 1.00
*/								
jQuery(document).ready(function ($) {  
	$(".youtube_colorbox").colorbox({rel: "how_to_videos", current: "video {current} of {total}", iframe:true, width:"90%", height:"90%"});
});

/*
* jQuery UI Tabs for Meta Box
* since 1.90
*/
jQuery(document).ready(function($) {
	
		//Variable from Localize Script
		var sections = cctor_coupon_option_js_vars.tabs_arr.replace(/&quot;/g, '"');
		
		var sections = jQuery.parseJSON(sections);
			
		var wrapped = $(".wrap h3").wrap("<div class=\"cctor-tabs-panel\">");
		
		wrapped.each(function() {
			$(this).parent().append($(this).parent().nextUntil("div.cctor-tabs-panel"));
		});
		$(".cctor-tabs-panel").each(function(index) {
			$(this).attr("id", sections[$(this).children("h3").text()]);
			if (index > 0)
				$(this).addClass("cctor-tabs-hide");
		});
	
		$(function() {
			//  http://stackoverflow.com/questions/4299435/remember-which-tab-was-active-after-refresh
			//	jQueryUI 1.10 and HTML5 ready
			//      http://jqueryui.com/upgrade-guide/1.10/#removed-cookie-option 
			//  Documentation
			//      http://api.jqueryui.com/tabs/#option-active
			//      http://api.jqueryui.com/tabs/#event-activate
			//      http://balaarjunan.wordpress.com/2010/11/10/html5-session-storage-key-things-to-consider/
			//

			//Get Query String Values
			var cctor_query_values = getQueryParams();
			
			//  Define friendly index name
			var index = "cctor-option-tab";
			//  Define friendly data store name
			var dataStore = window.sessionStorage;
			
			//If Saved then use tab index, otherwise default to first tab
			if (cctor_query_values["settings-updated"]) {
				//  Start magic!
				try {
					// getter: Fetch previous value
					var oldIndex = dataStore.getItem(index);
				} catch(e) {
					// getter: Always default to first tab in error state
					var oldIndex = 0;
				}
			} else {
				var oldIndex = 0;
			}
			
			// Tab initialization
			$(".cctor-tabs").tabs({
			   // The zero-based index of the panel that is active (open)
				active : oldIndex,
				// Triggered after a tab has been activated
				activate : function( event, ui ){
					//  Get future value
					var newIndex = ui.newTab.parent().children().index(ui.newTab);
					//  Set future value
					dataStore.setItem( index, newIndex ) 
				},
				fx: { opacity: "toggle", duration: "fast" },
			});
		}); 
	
		$("input[type=text], textarea").each(function() {
			if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
				$(this).css("color", "#999");
		});
		
		$("input[type=text], textarea").focus(function() {
			if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
				$(this).val("");
				$(this).css("color", "#000");
			}
		}).blur(function() {
			if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
				$(this).val($(this).attr("placeholder"));
				$(this).css("color", "#999");
			}
		});
		
		
		$(".wrap h3, .wrap table").show();
		
		// Browser compatibility
		if ($.browser.mozilla) 
			$("form").attr("autocomplete", "off");
});
		
		
/*
* Hide Row if Label is Empty
* since 1.80
*/					
jQuery(".form-table label:empty").parent().hide();

/*
* Retrieve Query String Parameter
* http://stackoverflow.com/questions/1171713/how-to-retrieve-query-string-parameter-and-values-using-javascript-jquery
* since 1.90
*/
function getQueryParams( val ) {
	//Use the window.location.search if we don't have a val.
	var query = val || window.location.search;
	query = query.split('?')[1]
	var pairs = query.split('&');
	var retval = {};
	var check = [];
	for( var i = 0; i < pairs.length; i++ ) {
		check = pairs[i].split('=');
		retval[decodeURIComponent(check[0])] = decodeURIComponent(check[1]);
	}

	return retval;
}