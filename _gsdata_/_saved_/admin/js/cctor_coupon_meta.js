/**
 * Update color picker element
 * Used for static & dynamic added elements (when clone)
 
 * http://stackoverflow.com/questions/19682706/how-do-you-close-the-iris-colour-picker-when-you-click-away-from-it
 */
 
jQuery(function() {
	jQuery(".datepicker").datepicker();
});
									
jQuery(document).ready(function(){

	jQuery(".youtube_colorbox").colorbox({rel: "how_to_videos", current: "video {current} of {total}", iframe:true, width:"90%", height:"90%"});
	

	var my_json_str = cctor_coupon_meta_js_vars.tabs_arr.replace(/&quot;/g, '"');

});

/*
* jQuery UI Tabs for Meta Box
*
* 	@version 1.90
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
		$(".cctor-tabs").tabs({
			fx: { opacity: "toggle", duration: "fast" }
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
* WP Color Picker
* @version 1.70
*/
jQuery(document).ready(function ($) {    

	var myOptions = {
    defaultColor: false,
    // a callback to fire whenever the color changes to a valid color
    change: function(event, ui){cctor_color_preview();},
    // a callback to fire when the input is emptied or an invalid color
    clear: function() {},
    // hide the color picker controls on load
    hide: true,
    // show a group of common colors beneath the square
    // or, supply an array of colors to customize further
    palettes: true
};


    $('.color-picker').wpColorPicker(myOptions);
	
	function cctor_color_preview() {
	console.log(this);
	
	var hexcolor = $('input.color-picker').val();
	
	console.log(hexcolor);
	$('.cctor_colordiscount').css('background', hexcolor);
}

//Discount Background Color
/*jQuery('#cctor_colordiscount').change(function(){
	
	console.log('change');
	
   var $this = jQuery(this);
   
   var color = jQuery(this).val();
   
   console.log($this.attr('id'));
   console.log(color);
	
   jQuery('.'+$this.attr('id')+'').css('background', color);
   
}); */

});


/*
* Media Manager 3.5
* @version 1.70
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
                text: 'Choose Coupon'
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
* Media Manager 3.5
* @version 1.70
*/
jQuery(document).ready(function($){
	//Remove Image and replace with default and Erase Image ID for Coupon
    $('.cctor_coupon_clear_image_button').click(function(e) {
        e.preventDefault();
		var coupon_remove_input_id = 'input#'+this.id+'.upload_coupon_image';
		var coupon_img_src = 'img#'+this.id+'.cctor_coupon_image';
		//var coupon_default_img_src = $('img#'+this.id+'.cctor_coupon_default_image').attr("src");
		console.log(coupon_remove_input_id);
		console.log(coupon_img_src);
		//console.log(coupon_default_img_src);
		
		$(coupon_remove_input_id).val('');
		$(coupon_img_src).hide();
		$('div#'+this.id+'.cctor_coupon_default_image').show();
    });
 
});