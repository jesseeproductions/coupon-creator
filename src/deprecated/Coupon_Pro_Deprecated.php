<?php

if ( ! class_exists( 'Cctor__Coupon__Pro__Expiration' ) && class_exists( 'CCtor_Pro_Expiration_Class' ) ) {
	
	class Cctor__Coupon__Pro__Expiration extends CCtor_Pro_Expiration_Class {

		public function __construct() {

			_deprecated_file( __CLASS__, '2.4', 'CCtor_Pro_Expiration_Class' );
		}

	}
}