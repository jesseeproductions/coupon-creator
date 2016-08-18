<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Image_Coupon' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Image_Coupon
 * Image Coupon Field
 */
class Cctor__Coupon__Admin__Field__Image_Coupon {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		if ( isset( $options_id ) && ! empty( $options_id ) ) {
			$name  = $options_id;
			$value = $options[ $field['id'] ];
		} else {
			$name  = $field['id'];
			$value = $meta;
		}

		$imagemsg = isset( $field['imagemsg'] ) ? $field['imagemsg'] : '';
		$class = isset( $field['class'] ) ? $field['class'] : '';
		$imagesrc = '';

		if ( is_numeric( $value ) ) {
			$imagesrc     = wp_get_attachment_image_src( absint( $value ), 'medium' );
			$imagesrc     = $imagesrc[0];
			$imagedisplay = '<div style="display:none" id="' . esc_attr( $field['id'] ) . '" class="pngx-default-image pngx-image-wrap">' . esc_html( $imagemsg ) . '</div> <img src="' . $imagesrc . '" id="' . esc_attr( $field['id'] ) . '" class="pngx-image pngx-image-wrap-img" />';
		} else {
			$imagedisplay = '<div style="display:block" id="' . esc_attr( $field['id'] ) . '" class="pngx-default-image pngx-image-wrap">' . esc_html( $imagemsg ) . '</div> <img style="display:none" src="' . $imagesrc . '" id="' . esc_attr( $field['id'] ) . '" class="pngx-image pngx-image-wrap-img" />';
		}

		echo $imagedisplay . '<br>';

		echo '<input class="pngx-upload-image ' . esc_attr( $class ) . '"  type="hidden" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $name ) . '" value="' . absint( $value ) . '" />';
		echo '<input id="' . esc_attr( $field['id'] ) . '" class="pngx-image-button" type="button" value="Upload Image" />';
		echo '<small> <a href="#" id="' . esc_attr( $field['id'] ) . '" class="pngx-clear-image">Remove Image</a></small>';


		if ( "" != $field['desc'] ) {
			echo '<br /><span class="description">' . $field['desc'] . '</span>';
		}

	}

}
