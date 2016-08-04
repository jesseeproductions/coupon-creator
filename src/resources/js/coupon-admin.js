/**
 * Init Tabs
 */
(function ( $ ) {

	var sections = jQuery.parseJSON( cctor_admin_js_vars.tabs_arr.replace( /&quot;/g, '"' ) );
	var updated_tab = cctor_admin_js_vars.cctor_coupon_updated;
	var coupon_id = cctor_admin_js_vars.cctor_coupon_id;
	pngx_admin_tabs.init( '.pngx-tabs', sections, updated_tab, coupon_id );

	var $data = [];
	var $toggle = [];

	$( '.pngx-meta-field-wrap' ).each( function () {
		if ( ! $.isEmptyObject( $( this ).data() ) ) {
			$data.push( $( this ).data() );
		}
	} );

	for ( var id in $data ) {

		//console.log($data[id]);
		//console.log($data[id].toggleInput);
		//console.log($data[id].toggleGroup);
		//console.log($data[id].toggleShow);
		//console.log($data[id].toggleMsg);

		if ( 'select#cctor_expiration_option' == $data[id].toggleInput  ) {

			console.log('change0');
			console.log($data[id].toggleGroup);
			pngx_fields_toggle.toggle(
				$data[id].toggleInput,
				$data[id].toggleGroup,
				$data[id].toggleShow + $( $data[id].toggleInput ).val(),
				$data[id].toggleMsg
			);

			/*pngx_fields_toggle.toggle_basic(
				'.expiration-field',
				$( $data[id].toggleInput ).val(),
				'.expiration-'
			);*/
			$( $data[id].toggleInput ).on( 'change', function () {
				console.log('change1');
					console.log($data[id].toggleGroup);
				console.log($data[id].toggleShow + $( this ).val());
				pngx_fields_toggle.toggle(
					$data[id].toggleInput,
					$data[id].toggleGroup,
					$data[id].toggleShow + $( this ).val(),
					$data[id].toggleMsg
				);
				/*pngx_fields_toggle.toggle_basic(
					'.expiration-field',
					$( this ).val(),
					'.expiration-'
				);*/
			} );

		}

		if ( 'input#cctor_image' == $data[id].toggleInput  ) {
			console.log('change2');
			console.log($data[id].toggleGroup);
			pngx_fields_toggle.toggle(
				$data[id].toggleInput,
				$data[id].toggleGroup,
				$data[id].toggleShow,
				$data[id].toggleMsg
			);

			$( $data[id].toggleInput ).on( 'display', function () {
				console.log('change3');
				console.log($data[id].toggleGroup);
				pngx_fields_toggle.toggle(
					$data[id].toggleInput,
					$data[id].toggleGroup,
					$data[id].toggleShow,
					$data[id].toggleMsg
				);
			} );

			$( ".pngx-clear-image" ).on( "click", function () {
				console.log('change4');
				console.log($data[id].toggleGroup);
				pngx_fields_toggle.toggle(
					$data[id].toggleInput,
					$data[id].toggleGroup,
					$data[id].toggleShow,
					$data[id].toggleMsg
				);
			} );
		}

	}

	//////REMOVE DOWN
	//Todo img coupon toggle - disable style message when pro active
	//todo img coupon toggle - disbale saw tooth border
	/*toremove = {};
	toremove.prepare = function( field_check, remove_img, messages   ) {

		if ( field_check == 'input#cctor_image' ) {

			var cctor_img_id = $( field_check ).val();

			//Continue if ID Found
			if ( cctor_img_id != '' || remove_img == true ) {

				var show_fields = [".cctor-img-coupon"];
				var dissable_style_fields_arr = '';

				if ( cctor_img_id != '' ) {

					//var border_disable = "sawtooth-border";
					//$( "select#cctor_coupon_border_themes" ).children( 'option[value="' + border_disable + '"]' ).prop( 'disabled', false );

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
	};*/
	//////REMOVE UP

})( jQuery );