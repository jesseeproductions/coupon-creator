/**
 * Update color picker element
 * Used for static & dynamic added elements (when clone)

 * http://stackoverflow.com/questions/19682706/how-do-you-close-the-iris-colour-picker-when-you-click-away-from-it
 */
var $ = jQuery.noConflict();
 
jQuery(document).ready(function($) {
	/*
	* WP Date Picker
	* since 1.70
	*/	
	$('#cctor_expiration').datepicker({
		beforeShow: function(input, inst) {
			 $("#ui-datepicker-div").addClass("cctor-ui")
		}
	});


	/*
	* WP Color Picker
	* since 1.70
	*/
	$('.color-picker').wpColorPicker();


	/*
	* Color Box Init for Help Videos
	* since 1.00
	*/
	$(".youtube_colorbox").colorbox({rel: "how_to_videos", current: "video {current} of {total}", iframe:true, width:"90%", height:"90%"});
	
});

/*
* Media Manager 3.5
* since 1.70
*/
jQuery(document).ready(function($){

    $('.coupon_image_button').click(function(e) {

		//Create Media Manager On Click to allow multiple on one Page
		var coupon_uploader;

        e.preventDefault();

		//Setup the Variables based on the Button Clicked to enable multiple
		var coupon_img_input_id = '#'+this.id+'.upload_coupon_image';
		var coupon_img_src = 'img#'+this.id+'.cctor_coupon_image';
		var coupon_default_msg = 'div#'+this.id+'.cctor_coupon_default_image';

        //If the uploader object has already been created, reopen the dialog
       if (coupon_uploader) {
            coupon_uploader.open();
            return;
        }

        //Extend the wp.media object
        coupon_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Coupon Image',
            button: {
                text: 'Use Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        coupon_uploader.on('select', function() {
            attachment = coupon_uploader.state().get('selection').first().toJSON();
			//Set the Field with the Image ID
            $(coupon_img_input_id).val(attachment.id);
			//Set the Sample Image with the URL
			$(coupon_img_src).attr('src', attachment.url);
			//Show Image
			$(coupon_img_src).show();
			//Hide Message
			$(coupon_default_msg).hide();
        });

        //Open the uploader dialog
        coupon_uploader.open();

    });

});
/*
* Remove Image
* since 1.70
*/
jQuery(document).ready(function($){
	//Remove Image and replace with default and Erase Image ID for Coupon
    $('.cctor_coupon_clear_image_button').click(function(e) {
        e.preventDefault();
		var coupon_remove_input_id = 'input#'+this.id+'.upload_coupon_image';
		var coupon_img_src = 'img#'+this.id+'.cctor_coupon_image';

		$(coupon_remove_input_id).val('');
		$(coupon_img_src).hide();
		$('div#'+this.id+'.cctor_coupon_default_image').show();
    });

});

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
*
* since 1.90
*/
jQuery(document).ready(function($) {

		//Variable from Localize Script
		var sections = cctor_coupon_meta_js_vars.tabs_arr.replace(/&quot;/g, '"');

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
			var index = "cctor-meta-tab" + cctor_query_values['post'];

			//  Define friendly data store name
			var dataStore = window.sessionStorage;

			//If Saved then use tab index, otherwise default to first tab
			if ( jQuery('.wrap .updated p').length ) {
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
		var tabTotallength = tabText + ( tabCount * 38 );
		
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
* Hide or Display Help Images
* 
* since 2.0
*/
function showHelp(helpid){

	var toggleImage = document.getElementById(helpid);

	 if(toggleImage.style.display == "inline") {
			 document.getElementById(helpid).style.display='none';
	 }else{
			document.getElementById(helpid).style.display='inline';
	 }
	 
	 return false;
}