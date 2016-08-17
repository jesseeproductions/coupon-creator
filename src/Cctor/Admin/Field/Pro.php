<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Pngx__Admin__Field__Pro' ) ) {
	return;
}


/**
 * Class Pngx__Admin__Field__Pro
 * Text Field
 */
class Pngx__Admin__Field__Pro {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		if ( isset( $options_id ) && ! empty( $options_id ) ) {
			$name  = $options_id;
			$value = $options[ $field['id'] ];
		} else {
			$name  = $field['id'];
			$value = $meta;
		}

		$size  = isset( $field['size'] ) ? $field['size'] : 30;
		$class = isset( $field['class'] ) ? $field['class'] : '';
		$std   = isset( $field['std'] ) ? $field['std'] : '';

		if ( isset( $field['alert'] ) && '' != $field['alert'] && 1 == cctor_options( $field['condition'] ) ) {
			echo '<div class="pngx-error">&nbsp;&nbsp;' . $field['alert'] . '</div>';
		}

		echo '<input type="text" class="regular-text ' . esc_attr( $class ) . '"  id="' . $field['id'] . '" name="' . esc_attr( $name ) . '" placeholder="' . esc_attr( $std ) . '" value="' . esc_attr( $value ) . '" size="' . absint( $size ) . '" />';

		if ( "" != $field['desc'] ) {
			echo '<br /><span class="description">' . $field['desc'] . '</span>';
		}

	}

}
