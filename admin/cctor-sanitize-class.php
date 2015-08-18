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
	private $type;

	/**
	 * the field's value
	 * @var mixed
	 */
	private $input;

	/**
	 * field variables
	 * @var array
	 */
	private $option;

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

		$this->type = $type;
		$this->input = $input;
		$this->option = $option;

		//Return Sanitized Input only if a method exists to sanitize the field type
		if ( method_exists( $this, 'cctor_sanitize_' . $this->type ) && is_callable(array( $this, 'cctor_sanitize_' . $this->type ) ) ) {

			//result
			$this->result = $this->{'cctor_sanitize_' . $this->type }( );

		} else {

			$this->result = false;
		}

		// return the result
		return $this->result;
	}

	/*
	* License Key Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_license( ) {

		return $this->cctor_sanitize_text( trim( $this->input ) );

	}

	/*
	* License Status Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_license_status( ) {

		return $this->cctor_sanitize_text( trim( $this->input ) );

	}

	/**
	* Sanitize Text
	*
	* @param  string $input A string
	* @since  2.1
	*
	* @return string         Sanitized version of the the text
	*/
	private function cctor_sanitize_text( ){

		return sanitize_text_field( $this->input );

	}

	/*
	* Sanitize Textarea
	* @version 2.1
	*/
	private function cctor_sanitize_textarea( ) {

		if ( $this->option['class'] != "code" ) {

			global $allowedtags;
			$input = wp_kses( $this->input, $allowedtags );

		} else {
			$input = wp_kses_post( $this->input );
		}

		return $input;
	}

	/*
	* Wysiwyg Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_wysiwyg( ) {

		if ( current_user_can( 'unfiltered_html' ) ) {
			$input = $this->input;
		}
		else {
			global $allowedtags;
			$input = wp_kses( $this->input, $allowedtags);
		}
		return $input;

	}

	/*
	* Sanitize urls
	* @version 2.1
	*/
	private function cctor_sanitize_url( ) {

		return esc_url( $this->input );

	}

	/*
	* Select Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_select( ) {

		return $this->cctor_sanitize_enum( $this->input , $this->option);

	}

	/*
	* Radio Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_radio( ) {

		return $this->cctor_sanitize_enum( $this->input, $this->option );

	}

	/*
	* Select and Radio Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_enum( ) {

		if ( array_key_exists( $this->input, $this->option['choices'] ) ) {
			$this->input = sanitize_text_field( $this->input );
		}
		return $this->input;
	}


	/*
	* Checkbox Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_checkbox( ) {
		if ( $this->input ) {
			$this->input = '1';
		} else {
			$this->input = false;
		}
		return $this->input;
	}

	/*
	* Sanitize Date
	* @version 2.1
	*/
	private function cctor_sanitize_date( ) {

		$this->input = preg_replace( "([^0-9/])", "", $this->input );

		return $this->input;
	}

	/*
	* Color Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_color( ) {

		if ( $this->cctor_validate_hex( $this->input ) ) {
			return $this->input;
		}
	}

	/*
	* Hex Sanitize
	* @version 2.1
	*/
	private function cctor_validate_hex( $hex ) {
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
	private function cctor_sanitize_image( ) {

		return $this->sanitize_absint( $this->input );

	}

	/*
	* Pro Image ID Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_proimage( ) {

		return $this->sanitize_absint( $this->input );

	}

	/*
	* Dimensions ID Sanitize
	* @version 2.1
	*/
	private function cctor_sanitize_dimensions( ) {

		return $this->sanitize_absint( $this->input );

	}

	/**
	* A 32bit absolute integer method, returns as String
	*
	* @param  string $input A numeric Integer
	* @since  2.1
	*
	* @return string         Sanitized version of the Absolute Integer
	*/
	private function sanitize_absint( ){
		// If it's not numeric we forget about it
		if ( ! is_numeric( $this->input ) ){
			return false;
		}

		$this->input = preg_replace( '/[^0-9]/', '', $this->input );

		// After the Replace return false if Empty
		if ( empty( $this->input ) ) {
			return false;
		}

		// After that it should be good to ship!
		return $this->input;
	}

	/*
	* Sanitize Google Analytics
	* @version 2.1
	*/
	private function cctor_sanitize_ga_analytics( ) {

		$this->input = trim( esc_html( $this->input ) );
		// en dash to minus, prevents issue with code copied from web with "fancy" dash
		$this->input = str_replace( '–', '-', $this->input );

		if ( ! preg_match( '|^UA-\d{4,}-\d+$|', $this->input ) ) {

			return false;

		} else {

			return $this->input;

		}

	}

} //end Coupon_Creator_Plugin_Sanitize Class