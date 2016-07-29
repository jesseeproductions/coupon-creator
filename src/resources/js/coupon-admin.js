/**
 * Init Tabs
 */
(function ( $ ) {

	var sections = jQuery.parseJSON( cctor_admin_js_vars.tabs_arr.replace( /&quot;/g, '"' ) );
	var updated_tab = cctor_admin_js_vars.cctor_coupon_updated;
	var coupon_id = cctor_admin_js_vars.cctor_coupon_id;
	pngx_admin_tabs.init( '.pngx-tabs', sections, updated_tab, coupon_id );


	/*
	 * Expiration Fields Display
	 * since 2.3
	 */
	var $expiration_option_val = $( '#cctor_expiration_option' );

	pngx_fields_toggle.toggle_basic( '#expiration .expiration-field', $expiration_option_val.val(), '#expiration .expiration-' );

	$expiration_option_val.on( 'change', function () {
		pngx_fields_toggle.toggle_basic( '#expiration .expiration-field', $( this ).val(), '#expiration .expiration-' );
	} );

	/*
	 * Core Meta Field Logic
	 */
	//Run Function to hide Content and Style Fields if Image Selected
	pngx_fields_toggle.prepare( 'input#cctor_image', 'initial' );
	$( "input#cctor_image" ).on( "display", function () {
		pngx_fields_toggle.prepare( 'input#cctor_image' );
	} );
	$( ".pngx-clear-image" ).on( "click", function () {
		pngx_fields_toggle.prepare( 'input#cctor_image', true );
	} );

})( jQuery );

/**
 * Fields Scripts
 * @type {{}}
 */
var cctor_admin_fields_init = cctor_admin_fields_init || {};
(function ( $, obj ) {
	'use strict';

	obj.init = function () {
		this.init_scripts();
	};

	obj.init_scripts = function () {

		/*
		 * WP Date Picker
		 */
		$( '#cctor_expiration' ).datepicker( {
			beforeShow: function ( input, inst ) {
				$( "#ui-datepicker-div" ).addClass( "cctor-ui" )
			}
		} );


		/*
		 * WP Color Picker
		 */
		$( '.color-picker' ).wpColorPicker();

		/*
		 * Media Manager 3.5
		 */
		$( '.pngx-image-button' ).click( function ( e ) {

			//Create Media Manager On Click to allow multiple on one Page
			var coupon_uploader;

			e.preventDefault();

			//Setup the Variables based on the Button Clicked to enable multiple
			var coupon_img_input_id = '#' + this.id + '.pngx-upload-image';
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
		 */
		//Remove Image and replace with default and Erase Image ID for Coupon
		$( '.pngx-clear-image' ).click( function ( e ) {
			e.preventDefault();
			var coupon_remove_input_id = 'input#' + this.id + '.pngx-upload-image';
			var coupon_img_src = 'img#' + this.id + '.cctor_coupon_image';

			$( coupon_remove_input_id ).val( '' );
			$( coupon_img_src ).hide();
			$( 'div#' + this.id + '.cctor_coupon_default_image' ).show();
			$( 'input#cctor_image' ).trigger( 'display' );
		} );

	};

	$( function () {
		obj.init();
	} );

})( jQuery, cctor_admin_fields_init );