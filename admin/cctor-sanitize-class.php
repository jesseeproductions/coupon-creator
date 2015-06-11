<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Sanitize Class
* @version 2.1
*/
class Coupon_Creator_Plugin_Sanitize {

	/**
	 * the field's type
	 * @var mixed
	 */
	public $type;

	/**
	 * the field's value
	 * @var mixed
	 */
	public $input;

	/**
	 * field variables
	 * @var array
	 */
	public $option;

	/**
	 * the result object of the validation
	 * @var stdClass
	 */
	public $result;
	/**
	 * class constructer
	 * init necessary functions
	 *
	 * @version 2.1
	 */
	function __construct( $type, $input, $option ) {

		// prepare object properties
		//$this->result          = new stdClass;
		$this->type           = $type;
		$this->input['id']    = $input;
		$this->option         = $option;

		//Return Sanitized Input only if a method exists to sanitize the field type
		if ( method_exists( $this, 'cctor_sanitize_'.$option['type'] ) && is_callable(array( $this, 'cctor_sanitize_'.$option['type'] ) ) ) {

			// set result
			$this->result = $this->{'cctor_sanitize_'.$option['type']}( $input, $option );

		} else {

			$this->result = '<br>No Validation Method Found for {cctor_sanitize_'.$option["type"].'}<br>';

			//$this->result = false;
		}

		// return the result
		return $this->result;
	}

	/*
	* License Key Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_license( $input ) {

		return $this->cctor_sanitize_text( trim( $input ) );

	}

	/*
	* License Status Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_license_status( $input ) {

		return $this->cctor_sanitize_text( trim( $input ) );

	}

	/**
	* Sanitize Text
	*
	* @param  string $input A string
	* @since  2.1
	*
	* @return string         Sanitized version of the the text
	*/
	public function cctor_sanitize_text( $input=null ){

		return sanitize_text_field( $input );

	}

	/*
	* Sanitize Textarea
	* @version 2.1
	*/
	public function cctor_sanitize_textarea( $input ) {

		global $allowedtags;

		$textarea = wp_kses( $input, $allowedtags);

		return $textarea;
	}

	/*
	* Textarea code Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_textarea_w_tags( $input ) {

		$input = wp_kses_post( $input );

		return $input;
	}

	/*
	* Wysiwyg Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_wysiwyg( $input ) {

		if ( current_user_can( 'unfiltered_html' ) ) {
			$input = $input;
		}
		else {
			global $allowedtags;
			$input = wp_kses( $input, $allowedtags);
		}
		return $input;

	}

	/*
	* Sanitize urls
	* @version 2.1
	*/
	public function cctor_sanitize_url( $input ) {

		return esc_url( $input );

	}

	/*
	* Select Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_select( $input, $option ) {

		return $this->cctor_sanitize_enum( $input , $option);

	}

	/*
	* Radio Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_radio( $input, $option ) {

		return $this->cctor_sanitize_enum( $input, $option );

	}

	/*
	* Select and Radio Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_enum( $input, $option ) {

		if ( array_key_exists( $input, $option['choices'] ) ) {
			$input = sanitize_text_field( $input );
		}
		return $input;
	}


	/*
	* Checkbox Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_checkbox( $input ) {
		if ( $input ) {
			$input = '1';
		} else {
			$input = false;
		}
		return $input;
	}

	/*
	* Sanitize Date
	* @version 2.1
	*/
	public function cctor_sanitize_date( $input ) {

		$input = preg_replace( "([^0-9/])", "", $input );

		return $input;
	}

	/*
	* Color Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_color( $input ) {

		if ( $this->cctor_validate_hex( $input ) ) {
			return $input;
		}
	}

	/*
	* Hex Sanitize
	* @version 2.1
	*/
	public function cctor_validate_hex( $hex ) {
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


	/*
	* Image ID Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_image( $input ) {

		return $this->sanitize_absint( $input );

	}

	/*
	* Pro Image ID Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_proimage( $input ) {

		return $this->sanitize_absint( $input );

	}

	/*
	* Dimensions ID Sanitize
	* @version 2.1
	*/
	public function cctor_sanitize_dimensions( $input ) {

		return $this->sanitize_absint( $input );

	}

	/**
	* A 32bit absolute integer method, returns as String
	*
	* @param  string $input A numeric Integer
	* @since  2.1
	*
	* @return string         Sanitized version of the Absolute Integer
	*/
	public function sanitize_absint( $input ){
		// If it's not numeric we forget about it
		if ( ! is_numeric( $input ) ){
			return false;
		}

		$input = preg_replace( '/[^0-9]/', '', $input );

		// After the Replace return false if Empty
		if ( empty( $input ) ) {
			return false;
		}

		// After that it should be good to ship!
		return $input;
	}

	/*
	* Sanitize Google Analytics
	* @version 2.1
	*/
	public function cctor_sanitize_ga_analytics( $input ) {

		$input = trim( esc_html( $input ) );
		// en dash to minus, prevents issue with code copied from web with "fancy" dash
		$input = str_replace( '–', '-', $input );

		if ( ! preg_match( '|^UA-\d{4,}-\d+$|', $input ) ) {

			return false;

		} else {

			return $input;

		}

	}


} //end Coupon_Creator_Plugin_Sanitize Class