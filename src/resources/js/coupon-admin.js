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
	 */
	var $expiration_option_val = $( '#cctor_expiration_option' );
	pngx_fields_toggle.toggle_basic( '#expiration .expiration-field', $expiration_option_val.val(), '#expiration .expiration-' );
	$expiration_option_val.on( 'change', function () {
		pngx_fields_toggle.toggle_basic( '#expiration .expiration-field', $( this ).val(), '#expiration .expiration-' );
	} );


	toremove = {};
	toremove.prepare = function( field_check, remove_img, messages   ) {

		if ( field_check == 'input#cctor_image' ) {

			var cctor_img_id = $( field_check ).val();

			//Continue if ID Found
			if ( cctor_img_id != '' || remove_img == true ) {

				var show_fields = [".cctor-img-coupon"];
				var dissable_style_fields_arr = '';

				if ( cctor_img_id != '' ) {

					var border_disable = "sawtooth-border";
					$( "select#cctor_coupon_border_themes" ).children( 'option[value="' + border_disable + '"]' ).prop( 'disabled', false );

					dissable_style_fields_arr = [".cctor-img-coupon"];

					if ( !$( '.pngx-tabs .pngx-tab-heading-links' ).length ) {
						var messages = {
							".pngx-tab-heading-content": cctor_meta_js.cctor_disable_content_msg,
							".pngx-tab-heading-style": cctor_meta_js.cctor_disable_style_msg
						};
					} else {
						var messages = {
							".pngx-tab-heading-content": cctor_meta_js.cctor_disable_content_msg,
							".pngx-tab-heading-style": ''
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

		obj.toggle( field_check, dissable_style_fields_arr, show_fields, message_div );
	};


	var messages = {
		".pngx-tab-heading-content": cctor_meta_js.cctor_disable_content_msg,
		".pngx-tab-heading-style": cctor_meta_js.cctor_disable_style_msg
	};

	var data = $( 'input#cctor_image' ).data();
	console.log(data);

	var $data_fields = [];

	$( '.pngx-meta-field-wrap' ).each( function () {
		if ( '' != $( this ).data() ) {
			//var $data_field[] = this.data();
			console.log($( this ).data());
		}
	} );
	console.log($data_fields);
	///for ( var field in data ) {

	//}
	pngx_fields_toggle.toggle( 'input#cctor_image', 'initial', messages );


	/*$( "input#cctor_image" ).on( "display", function () {
		pngx_fields_toggle.toggle( 'input#cctor_image' );
	} );*/
	/*$( ".pngx-clear-image" ).on( "click", function () {
		pngx_fields_toggle.toggle( 'input#cctor_image', true, messages );
	} );*/

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
		 * Remove Image and replace with default and Erase Image ID for Coupon
		 */
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