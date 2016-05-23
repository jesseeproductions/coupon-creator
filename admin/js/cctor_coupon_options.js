/*
* WP Color Picker
* since 1.70
*/
var $ = jQuery.noConflict();

jQuery(document).ready(function ($) {    

  	/*
	* Colorpicker JS
	* since 1.00
	*/
    $('.color-picker').wpColorPicker();


	/*
	* Color Box Init for Help Videos
	* since 1.00
	*/
	$(".youtube_colorbox").colorbox({rel: "how_to_videos", current: "video {current} of {total}", iframe:true, width:"90%", height:"90%"});

	/*
	* Add Class to Show that no jQuery Errors
	* since 2.3
	*/
	$('html').addClass('cctor-js');

});

/*
*	Calculate Width of Text
*	http://stackoverflow.com/questions/1582534/calculating-text-width-with-jquery
* 	since 2.0
*/
$.fn.textWidth = function(text, font) {
    if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
    $.fn.textWidth.fakeEl.text(text || this.val() || this.text()).css('font', font || this.css('font'));
    return $.fn.textWidth.fakeEl.width();
};

/*
* Toogle Slide Responsive Mode Tabs
* since 2.0
*/
function toggleMobileMenu(event, tabClass) {

	tabClass = tabClass.slice(0, -7)

	$('.'+tabClass).slideToggle();

}

/*
* jQuery UI Tabs for Meta Box
* since 1.90
*/
jQuery(document).ready(function($) {
	
		//Variable from Localize Script
		var sections = cctor_coupon_option_js_vars.tabs_arr.replace(/&quot;/g, '"');
		
		var sections = jQuery.parseJSON(sections);

		//Get Correct Selectors for 4.4 and greater
		var wrap_id = '.wrap h2';
		var wrap_panel_id = 'h2';
		if ( cctor_coupon_option_js_vars.wp_version < '4.4' ) {
			var wrap_id = '.wrap h3';
			var wrap_panel_id = 'h3';
		}

		var wrapped = $(wrap_id).wrap("<div class=\"cctor-tabs-panel\">");
		
		wrapped.each(function() {
			$(this).parent().append($(this).parent().nextUntil("div.cctor-tabs-panel"));
		});
		$(".cctor-tabs-panel").each(function(index) {
			$(this).attr("id", sections[$(this).children(wrap_panel_id).text()]);
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
			var cctor_options_updated = cctor_coupon_option_js_vars.cctor_options_updated

			//  Define friendly index name
			var index = "cctor-option-tab";
			//  Define friendly data store name
			var dataStore = window.sessionStorage;
			
			//If Saved then use tab index, otherwise default to first tab
			if ( cctor_options_updated.length != 0 ) {
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
					
					//Set Responsive Menu Text to Current Tab
					var selectedTab = $('.cctor-tabs').tabs('option', 'active');					
					$('.cctor-tabs-nav-mobile').text( $('.cctor-tabs ul li a').eq(selectedTab).text() );
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
*	Calculate Width of Text
*	http://stackoverflow.com/questions/1582534/calculating-text-width-with-jquery
* 	since 2.0
*/
$.fn.textWidth = function(text, font) {
    if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
    $.fn.textWidth.fakeEl.text(text || this.val() || this.text()).css('font', font || this.css('font'));
    return $.fn.textWidth.fakeEl.width();
};

/*
* Toogle Slide Responsive Mode Tabs
* since 2.0
*/
function toggleMobileMenu(event, tabClass) {

	tabClass = tabClass.slice(0, -7)

	$('.'+tabClass).slideToggle();

}
	
/*
* 	Responsive Tabs Find Breakpoint to Change or Accordion Layout or Back to Tabs
* 	since 2.0
*/
jQuery(function($) {
	
	//Calculate Total Tab Length to determine when to switch between Responsive and Regular Tabs
	var tabText = 0;
	var tabCount = 0;
	
	$(".cctor-tabs-nav li").each(function() {
	
		tabText = tabText + $(this).textWidth();
		
		tabCount = tabCount + 1;
		
	});
	
	//On Resize or Load check if Tabs will fit
	$(window).on('resize load',function(e){
				
		// 38px per tab for padding	
		var tabTotallength = tabText + ( tabCount * 40 );
		
		if ( tabTotallength > $('.cctor-tabs').width() ) {
			$('.cctor-tabs-nav').addClass( 'cctor_accordiantabs' );
			$('.cctor-tabs-nav-mobile').addClass( 'show' );
			
		} else {
			$('.cctor-tabs-nav').fadeIn( 'fast', function() {
				$(this).removeClass( 'cctor_accordiantabs' );
			});
			$('.cctor-tabs-nav-mobile').removeClass( 'show' );
		}	
	});
	
});

/*
* Tabs Responsive Mode
* 
* since 2.0
*/
jQuery(document).ready(function($) {
	
	$('.cctor-tabs-nav').before( '<div class="cctor-tabs-nav-mobile">Menu</div>' );
	
	//Change Menu Text on Creation of Tabs
	$( ".cctor-tabs" ).on( "tabscreate", function( event, ui ) { 
		
		var selectedTab = $('.cctor-tabs').tabs('option', 'active');		
		
		$('.cctor-tabs-nav-mobile').text( $('.cctor-tabs ul li a').eq(selectedTab).text() );
		
	});
	
	//Open Tabs in Responsive Mode
	$(document).on('click', '.cctor-tabs-nav-mobile', function(event) {

		var tabClass = $(this).attr('class').split(" ")[0];

		toggleMobileMenu(event, tabClass);
	})

});
		
/*
* Hide Row if Label is Empty
* since 1.80
*/					
jQuery(".form-table label:empty").parent().hide();


jQuery(document).ready(function ($) {

	/*
	* Add Class to Show that no jQuery Errors
	* since 2.3
	*/
	$('html').addClass('cctor-js');

	/*
	* Hide Default Label
	* since 2.3
	*/
	$("tbody tr th:contains(Default)").css("display", "none");


	/*
	 * Help Slideout
	 * since 2.3
	 */
	$( ".cctor-section-help-container-toggle" ).on( "click", function ( event ) {
		event.preventDefault();

		$help_wrap = $( this ).parent();
		var $help_section = $help_wrap.find( '.cctor-section-help-slideout' );

		$help_section.animate( {
			height: "toggle",
			opacity: "toggle"
		}, "fast" );
	} );

});