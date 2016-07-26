/**
 * Init Tabs
 */
(function( $ ) {

	var sections =  jQuery.parseJSON( cctor_coupon_meta_js_vars.tabs_arr.replace( /&quot;/g, '"' ) );
	var updated_tab =  cctor_coupon_meta_js_vars.cctor_coupon_updated;
	var coupon_id =  cctor_coupon_meta_js_vars.cctor_coupon_id;
	pngx_admin_tabs.init( sections, updated_tab, coupon_id );

})( jQuery );


/**
 * Update color picker element
 * Used for static & dynamic added elements (when clone)

 * http://stackoverflow.com/questions/19682706/how-do-you-close-the-iris-colour-picker-when-you-click-away-from-it
 */
var $ = jQuery.noConflict();

jQuery( function ( $ ) {
	/*
	 * WP Date Picker
	 * since 1.70
	 */
	$( '#cctor_expiration' ).datepicker( {
		beforeShow: function ( input, inst ) {
			$( "#ui-datepicker-div" ).addClass( "cctor-ui" )
		}
	} );


	/*
	 * WP Color Picker
	 * since 1.70
	 */
	$( '.color-picker' ).wpColorPicker();


	/*
	 * Color Box Init for Help Videos
	 * since 1.00
	 */
	$( ".youtube_colorbox" ).colorbox( {
		rel: "how_to_videos",
		current: "video {current} of {total}",
		iframe: true,
		width: "90%",
		height: "90%"
	} );


	/*
	 * Media Manager 3.5
	 * since 1.70
	 */
	$( '.coupon_image_button' ).click( function ( e ) {

		//Create Media Manager On Click to allow multiple on one Page
		var coupon_uploader;

		e.preventDefault();

		//Setup the Variables based on the Button Clicked to enable multiple
		var coupon_img_input_id = '#' + this.id + '.upload_coupon_image';
		var coupon_img_src = 'img#' + this.id + '.cctor_coupon_image';
		var coupon_default_msg = 'div#' + this.id + '.cctor_coupon_default_image';

		//If the uploader object has already been created, reopen the dialog
		if ( coupon_uploader ) {
			coupon_uploader.open();
			return;
		}

		//Extend the wp.media object
		coupon_uploader = wp.media.frames.file_frame = wp.media( {
			title: 'Choose Coupon Image',
			button: {
				text: 'Use Image'
			},
			multiple: false
		} );

		//When a file is selected, grab the URL and set it as the text field's value
		coupon_uploader.on( 'select', function () {
			attachment = coupon_uploader.state().get( 'selection' ).first().toJSON();
			//Set the Field with the Image ID
			$( coupon_img_input_id ).val( attachment.id );
			//Set the Sample Image with the URL
			$( coupon_img_src ).attr( 'src', attachment.url );
			//Show Image
			$( coupon_img_src ).show();
			//Hide Message
			$( coupon_default_msg ).hide();
			//Trigger New Image Uploaded
			$( 'input#cctor_image' ).trigger( 'display' );
		} );

		//Open the uploader dialog
		coupon_uploader.open();

	} );

	/*
	 * Remove Image
	 * since 1.70
	 */
	//Remove Image and replace with default and Erase Image ID for Coupon
	$( '.cctor_coupon_clear_image_button' ).click( function ( e ) {
		e.preventDefault();
		var coupon_remove_input_id = 'input#' + this.id + '.upload_coupon_image';
		var coupon_img_src = 'img#' + this.id + '.cctor_coupon_image';

		$( coupon_remove_input_id ).val( '' );
		$( coupon_img_src ).hide();
		$( 'div#' + this.id + '.cctor_coupon_default_image' ).show();
		$( 'input#cctor_image' ).trigger( 'display' );
	} );

} );

/*
 * Hide or Display Help Images
 *
 * since 2.0
 */
function showHelp( helpid ) {

	var toggleImage = document.getElementById( helpid );

	if ( toggleImage.style.display == "inline" ) {
		document.getElementById( helpid ).style.display = 'none';
	} else {
		document.getElementById( helpid ).style.display = 'inline';
	}

	return false;
}

/*
 * Toogle Meta Field Display
 * @version 2.1
 *
 */
function cctor_prepare_toggle_fields( field_check, remove_img ) {

/*	if ( field_check == 'input#cctor_image' ) {

		var cctor_img_id = $( field_check ).val();
		//Continue if ID Found
		if ( cctor_img_id != '' || remove_img == true ) {

			var show_fields = [".cctor-img-coupon"];
			var dissable_style_fields_arr = '';
			var message_div = {
				".cctor-tab-heading-content": '',
				".cctor-tab-heading-style": ''
			};

			if ( cctor_img_id != '' ) {

				var border_disable = "sawtooth-border";
				$( "select#cctor_coupon_border_themes" ).children( 'option[value="' + border_disable + '"]' ).prop( 'disabled', false );

				dissable_style_fields_arr = [".cctor-img-coupon"];

				if ( !$( '.cctor-tabs .cctor-tab-heading-links' ).length ) {
					var message_div = {
						".cctor-tab-heading-content": cctor_meta_js.cctor_disable_content_msg,
						".cctor-tab-heading-style": cctor_meta_js.cctor_disable_style_msg
					};
				} else {
					var message_div = {
						".cctor-tab-heading-content": cctor_meta_js.cctor_disable_content_msg,
						".cctor-tab-heading-style": ''
					};

					//If Saw Tooth Border is Selected then change as it does not work with the Image Coupon
					if ( $( "#cctor_coupon_border_themes option:selected" ).val() == border_disable ) {

						$( "select#cctor_coupon_border_themes" ).prop( "selectedIndex", 0 );

						if ( typeof cctor_pro_prepare_toggle_fields == 'function' ) {

							cctor_pro_prepare_toggle_fields( '#cctor_coupon_border_themes' );
						}
					}
					$( "select#cctor_coupon_border_themes" ).children( 'option[value="' + border_disable + '"]' ).prop( 'disabled', true );
				}
			}
		}
	}

	cctor_toggle_fields( field_check, dissable_style_fields_arr, show_fields, message_div );*/
}

/*
 * Toggle Meta Field Display
 * @version 2.1
 *
 */
function cctor_toggle_fields( field_check, field_display, show_fields, message_div ) {
	if ( ( $( field_check ).prop( 'checked' ) ) || ( field_check == '' && cctor_pro_meta_js.cctor_disable_print == 1 ) ) {
		$.each( field_display, function ( index, field_class ) {
			$( field_class ).fadeOut();
			$( field_class + " input:text" ).val( '' );
			$( field_class + " input:checked" ).removeAttr( 'checked' );
		} );
	} else if ( field_check && show_fields ) {
		$.each( show_fields, function ( index, field_class ) {
			$( field_class ).fadeIn( 'fast' );
		} );
		if ( field_display ) {
			$.each( field_display, function ( index, field_class ) {
				$( field_class ).fadeOut();
			} );
		}
	} else if ( field_display ) {
		$.each( field_display, function ( index, field_class ) {
			$( field_class ).fadeIn();
		} );
	}
	//Only Run if Variable is an Object
	if ( typeof message_div === 'object' ) {
		//Remove Message
		$.each( message_div, function ( key, value ) {
			$( key ).next( 'div.cctor-error' ).remove();
		} );
		//Add Message
		$.each( message_div, function ( key, value ) {
			if ( key && value ) {
				$( key ).after( '<div class="cctor-error">' + value + '</div>' );
			}
		} );
	}

}

/*
 * Core Meta Field Logic
 * @version 2.1
 *
 */
jQuery( function ( $ ) {
	//Run Function to hide Content and Style Fields if Image Selected
	cctor_prepare_toggle_fields( 'input#cctor_image', 'initial' );
	$( "input#cctor_image" ).on( "display", function () {
		cctor_prepare_toggle_fields( 'input#cctor_image' );
	} );
	$( ".cctor_coupon_clear_image_button" ).on( "click", function () {
		cctor_prepare_toggle_fields( 'input#cctor_image', true );
	} );
} );

jQuery( function ( $ ) {

	/*
	 * Expiration Fields Display
	 * since 2.3
	 */
	var $expiration_option_val = $( '#cctor_expiration_option' );

	cctor_toogle_fields( '#expiration .expiration-field', $expiration_option_val.val(), '#expiration .expiration-' );

	$expiration_option_val.on( 'change', function () {
		cctor_toogle_fields( '#expiration .expiration-field', $( this ).val(), '#expiration .expiration-' );
	} );


} );

/*
 * Toogle Meta Field Display
 * @version 2.3
 *
 */
function cctor_toogle_fields( common_wrap, value, selector ) {

	var $selector = selector + value;

	//Hide All Fields with Common Wrap
	$( common_wrap ).each( function () {
		$( this ).css( 'display', 'none' );
	} );

	//Show Fields Based on Value of a Field
	$( $selector ).each( function () {
		$( this ).css( 'display', 'block' );
	} );

}

/*
 * Add Class to Show that no jQuery Errors
 * since 2.3
 */
jQuery( function ( $ ) {
	$( 'html' ).addClass( 'cctor-js' );
} );