/**
 * Init Tabs
 */
(function ( $ ) {

	//var sections = jQuery.parseJSON( cctor_admin_js_vars.tabs_arr.replace( /&quot;/g, '"' ) );
	//var updated_tab = cctor_admin_js_vars.cctor_coupon_updated;
	//var coupon_id = cctor_admin_js_vars.cctor_coupon_id;
	pngx_admin_tabs.init( '.main.pngx-tabs' );

	var $data = [];
	var $toggle = [];

	$( '.pngx-meta-field-wrap' ).each( function () {
		if ( !$.isEmptyObject( $( this ).data() ) ) {
			$data.push( $( this ).data() );
		}
	} );

	//Create Image Upload Object
	var coupon_img = new PNGX__Media( $, 'input#cctor_image', 'Choose Coupon Image', 'Use Image' );

	//for ( var id in $data ) {

	//console.log(id);
	//console.log($data[id].toggleField);
	//console.log($data[id].toggleGroup);
	//console.log($data[id].toggleShow);
	//console.log($data[id].toggleMsg);
	if ( $data && $data.length ) {
		if ( 'select#cctor_expiration_option' == $data[0].toggleField ) {

			//console.log('change0');
			console.log($data[0]);
			pngx_fields_toggle.toggle(
				$data[0].toggleField,
				$data[0].toggleGroup,
				$data[0].toggleShow + $( $data[0].toggleField ).val(),
				$data[0].toggleMsg
			);

			/*pngx_fields_toggle.toggle_basic(
			 '.expiration-field',
			 $( $data[id].toggleField ).val(),
			 '.expiration-'
			 );*/
			$( $data[0].toggleField ).on( 'change', function () {
				//console.log('change1');
				//	console.log($data[0].toggleGroup);
				//console.log($data[0].toggleShow + $( this ).val());
				pngx_fields_toggle.toggle(
					$data[0].toggleField,
					$data[0].toggleGroup,
					$data[0].toggleShow + $( this ).val(),
					$data[0].toggleMsg
				);
				/*pngx_fields_toggle.toggle_basic(
				 '.expiration-field',
				 $( this ).val(),
				 '.expiration-'
				 );*/
			} );

		}

		if ( 'input#cctor_image' == $data[1].toggleField ) {
			//console.log('change2');
			//console.log( $data[1] );
			pngx_fields_toggle.toggle(
				$data[1].toggleField,
				$data[1].toggleGroup,
				$data[1].toggleShow,
				$data[1].toggleMsg
			);

			$( $data[1].toggleField ).on( 'display', function () {
				//console.log('change3');
				//console.log($data[1].toggleGroup);
				pngx_fields_toggle.toggle(
					$data[1].toggleField,
					$data[1].toggleGroup,
					$data[1].toggleShow,
					$data[1].toggleMsg
				);
			} );

			$( ".pngx-clear-image" ).on( "click", function () {
				//console.log('change4');
				//console.log($data[1].toggleGroup);
				pngx_fields_toggle.toggle(
					$data[1].toggleField,
					$data[1].toggleGroup,
					$data[1].toggleShow,
					$data[1].toggleMsg
				);
			} );
		}
	}

	//}
	//////REMOVE DOWN
	//Todo img coupon toggle - disable style message when pro active
	//todo img coupon toggle - disbale saw tooth border
	/*toremove = {};
	 toremove.prepare = function( field_check, remove_img, messages   ) {

	 if ( field_check == 'input#cctor_image' ) {

	 var cctor_img_id = $( field_check ).val();

	 //Continue if ID Found
	 if ( cctor_img_id != '' || remove_img == true ) {

	 var show_fields = [".image-coupon-disable"];
	 var dissable_style_fields_arr = '';

	 if ( cctor_img_id != '' ) {

	 //var border_disable = "sawtooth-border";
	 //$( "select#cctor_coupon_border_themes" ).children( 'option[value="' + border_disable + '"]' ).prop( 'disabled', false );

	 dissable_style_fields_arr = [".image-coupon-disable"];

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
	 };*/
	//////REMOVE UP

})( jQuery );