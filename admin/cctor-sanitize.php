<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Sanitize Filters
* @version 1.80
*/

/*
* Sanitize Text
* @version 1.80
*/	
add_filter( 'cctor_sanitize_text', 'sanitize_text_field' );

/*
* Sanitize Textarea
* @version 1.80
*/
add_filter( 'cctor_sanitize_textarea', 'cctor_sanitize_textarea' );
function cctor_sanitize_textarea( $input) {
	global $allowedtags;
	$textarea = wp_kses( $input, $allowedtags);
	return $textarea;
}

/*
* Select and Radio Sanitize
* @version 1.80
*/	
function cctor_sanitize_enum( $input, $option ) {

	$output = '';
	if ( array_key_exists( $input, $option['choices'] ) ) {
		$output = $input;
	}
	return $output;
}
/* Select */
add_filter( 'cctor_sanitize_select', 'cctor_sanitize_enum', 10, 2 );

/* Radio */
add_filter( 'cctor_sanitize_radio', 'cctor_sanitize_enum', 10, 2 );

/*
* Image Field Sanitize
* @version 1.80
*/	
add_filter( 'cctor_sanitize_image', 'sanitize_text_field' );

/*
* Checkbox Sanitize
* @version 1.80
*/	
function cctor_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}
add_filter( 'cctor_sanitize_checkbox', 'cctor_sanitize_checkbox' );

/*
* Wysiwyg Sanitize
* @version 1.80
*/	
function cctor_sanitize_wysiwyg( $input ) {

	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		global $allowedtags;
		$output = wpautop(wp_kses( $input, $allowedtags));
	}
	return $output;
}
add_filter( 'cctor_sanitize_wysiwyg', 'cctor_sanitize_wysiwyg' );
/*
* Textarea code Sanitize
* @version 1.80
*/	
function cctor_sanitize_textarea_w_tags( $input ) {

	if ( current_user_can( 'unfiltered_html' ) ) {
		
		$output = stripslashes($input);
	}
	else {
		//Tags for textarea code
		$cctor_allowedtags = array(
				'a' => array(
					'href' => array(),
					'alt' => array(),					
					'title' => array()
				),
				'img' => array(
					'src' => array(),
					'title' => array()
				),				
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'p' => array(),
				'ul' => array(),
				'ol' => array(),
				'li' => array()		
			);

		$output = wp_kses( $input, $cctor_allowedtags);
	}
	return $output;
}
add_filter( 'cctor_sanitize_textarea_w_tags', 'cctor_sanitize_textarea_w_tags' );
/*
* Color Sanitize
* @version 1.80
*/	
function cctor_sanitize_hex( $hex, $default = '' ) {
	if ( cctor_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}
/* Check if Valid String with or without opening # */
function cctor_validate_hex( $hex ) {
	$hex = trim( $hex );
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}	
add_filter( 'cctor_sanitize_color', 'cctor_sanitize_hex' );
/*
* Sanitize Date
* @version 1.80
*/	
add_filter( 'cctor_sanitize_date', 'sanitize_text_field' );

/*
* Sanitize Dimensions
* @version 1.80
*/	
add_filter( 'cctor_sanitize_dimensions', 'sanitize_dimension_field' );
function sanitize_dimension_field( $input, $default = "") {
	//If Number and Positive
	if (is_numeric($input) && $input >= 0 ) {
		return $input;
	} else {
		return $default;
	} 
}