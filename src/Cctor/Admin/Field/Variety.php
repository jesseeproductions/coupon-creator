<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Variety' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Variety
 * Select Field
 */
class Cctor__Coupon__Admin__Field__Variety {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		global $pagenow;
		$selected = '';

		if ( isset( $options_id ) && ! empty( $options_id ) ) {
			$name     = $options_id;
			$selected = $options[ $field['id'] ] ? $options[ $field['id'] ] : $field['std'];
		} else {
			$name = $field['id'];

			// Set Meta Default
			if ( $meta ) {
				$selected = $meta;
			} elseif ( 'post-new.php' === $pagenow && isset( $field['value'] ) ) {
				$selected = $field['value'];
			}
		}


		if ( $meta ) {
			$selected = $meta;
		} elseif ( 'post-new.php' === $pagenow && isset( $field['value'] ) ) {
			$selected = $field['value'];
		}

		$class = isset( $field['class'] ) ? $field['class'] : '';

		?>
		<div class="pngx-one-third pngx-first">
			<select id="<?php echo esc_attr( $field['id'] ); ?>" class="select <?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $name ); ?>"><
				<?php
				foreach ( $field['choices'] as $value => $label ) {

					$style = isset( $field['class'] ) && 'css-select' === $field['class'] ? 'style="' . esc_attr( $value ) . '"' : '';

					echo '<option ' . $style . ' value="' . esc_attr( $value ) . '"' . selected( $selected, $value, false ) . '>' . esc_attr( $label ) . '</option>';

				}
				?>
			</select>
		</div>
		<div class="pngx-two-thirds">
			<?php
			$fields = apply_filters( 'cctor_filter_meta_template_fields', array() );;
			global $post;

			foreach ( $field['choices'] as $name => $label ) {

				if ( ! isset( $fields[ 'cctor_' . $name ] ) ) {
					continue;
				}

				$meta = get_post_meta( $post->ID, 'cctor_' . $name, true );
				Pngx__Admin__Fields::display_field( $fields[ 'cctor_' . $name ], false, false, $meta, null );

			}
			?>

		</div>
		<?php
		if ( '' !== $field['desc'] ) {
			echo '<br /><span class="description">' . esc_html( $field['desc'] ) . '</span>';
		}

	}

}
