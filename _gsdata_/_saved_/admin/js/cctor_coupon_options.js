/*
* WP Color Picker
* @version 1.70
*/
jQuery(document).ready(function ($) {    
    $('.color-picker').wpColorPicker();
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
		var coupon_default_img_src = $('img#'+this.id+'.cctor_coupon_default_image').attr("src");
		
		$(coupon_remove_input_id).val('');
		$(coupon_img_src).attr('src', coupon_default_img_src);
    });
 
});