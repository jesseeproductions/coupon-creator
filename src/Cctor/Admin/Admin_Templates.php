<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Class Cctor__Coupon__Admin__Admin_Templates
 *
 */
class Cctor__Coupon__Admin__Admin_Templates {


	public function __construct() {

		$this->start();

	}

	protected function start() {

		add_action( 'wp_ajax_cctor_templates', [ $this, 'load_templates' ] );

		add_action( 'wp_ajax_cctor_variety', [ $this, 'load_variety' ] );
		//add_action( 'wp_ajax_nopriv_cctor_templates', [ $this, 'load_templates' ] );

	}

	public function load_templates() {

		global $wp_version;

		//log_me( 'load_templates' );
		//log_me( $_POST );

		//End if not the correct action
		if ( ! isset( $_POST['action'] ) || 'cctor_templates' != $_POST['action'] ) {
			wp_send_json_error( __( 'Permission Error', 'coupon-creator' ) );
		}

		//End if not correct nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'download_click_counter_' . $_POST['post_id'] ) ) {
			wp_send_json_error( __( 'Permission Error', 'coupon-creator' ) );
		}

		//End if no ID or title
		//if ( ! isset( $_POST['post_id'] ) ) {
		//	wp_send_json_error( __( 'No Extension ID', 'coupon-creator' ) );
		//}

		if ( ! isset( $_POST['template'] ) ) {
			wp_send_json_error( __( 'No Template ID', 'coupon-creator' ) );
		}

		Pngx__Main::instance()->doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		ob_start();

		$fields = Cctor__Coupon__Admin__Meta::instance()->get_fields();

		//log_me( 'start' );

		foreach ( $fields as $field ) {

			$field_template = isset( $field['template'] ) ? $field['template'] : array();

			if ( $field['type'] && in_array( $_POST['template'], $field_template ) ) {

				// get value of this field if it exists for this post
				$meta = get_post_meta( $_POST['post_id'], $field['id'], true );

				//Wrap Class for Conditionals
				$wrapclass = isset( $field['wrapclass'] ) ? $field['wrapclass'] : '';

				?>

				<div class="pngx-meta-field-wrap field-wrap-<?php echo esc_html( $field['type'] ); ?> field-wrap-<?php echo esc_html( $field['id'] ); ?> <?php echo esc_html( $wrapclass ); ?>"
					<?php echo isset( $field['toggle'] ) ? Pngx__Admin__Fields::toggle( $field['toggle'], $field['id'] ) : null; ?> >

					<?php if ( isset( $field['label'] ) ) { ?>

						<div class="pngx-meta-label label-<?php echo $field['type']; ?> label-<?php echo $field['id']; ?>">
							<label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
						</div>

					<?php } ?>

					<div class="pngx-meta-field field-<?php echo $field['type']; ?> field-<?php echo $field['id']; ?>">

						<?php

						Pngx__Admin__Fields::display_field( $field, false, false, $meta, $wp_version );

						// Display admin linked style fields
						Pngx__Admin__Style__Linked::display_styles( $fields, $field, $_POST['post_id'] );

						?>

					</div>
					<!-- end .pngx-meta-field.field-<?php echo $field['type']; ?>.field-<?php echo $field['id']; ?> -->

				</div> <!-- end .pngx-meta-field-wrap.field-wrap-<?php echo $field['type']; ?>.field-wrap-<?php echo $field['id']; ?>	-->

				<?php
			}
		} // end foreach fields
		//log_me( 'end' );
		$template_fields = ob_get_contents();
		//log_me( $template_fields );

		ob_end_clean();
		//log_me( 'success' );
		wp_send_json_success( json_encode( $template_fields ) );
	}

	public function load_variety() {

		//End if not the correct action
		if ( ! isset( $_POST['action'] ) || 'cctor_variety' != $_POST['action'] ) {
			wp_send_json_error( __( 'Permission Error', 'coupon-creator' ) );
		}

		//End if not correct nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'download_click_counter_' . $_POST['post_id'] ) ) {
			wp_send_json_error( __( 'Permission Error', 'coupon-creator' ) );
		}

		if ( ! isset( $_POST['field'] ) ) {
			wp_send_json_error( __( 'No Field ID', 'coupon-creator' ) );
		}

		if ( ! isset( $_POST['option'] ) ) {
			wp_send_json_error( __( 'No Option ID', 'coupon-creator' ) );
		}

		Pngx__Main::instance()->doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		ob_start();

		$fields = Cctor__Coupon__Admin__Meta::instance()->get_fields();

		if ( isset( $fields[ $_POST['field'] ]['variety_choices'][ $_POST['option'] ] ) ) {
			foreach ( $fields[ $_POST['field'] ]['variety_choices'][ $_POST['option'] ] as $label ) {

				if ( ! isset( $fields[ 'cctor_' . $label ] ) ) {
					continue;
				}
				$meta = '';
				if ( isset( $_POST['post_id'] ) ) {
					$meta = get_post_meta( $_POST['post_id'], 'cctor_' . $label, true );
				}
				Pngx__Admin__Fields::display_field( $fields[ 'cctor_' . $label ], false, false, $meta, null );

			}
		}

		$field = ob_get_contents();

		ob_end_clean();

		wp_send_json_success( json_encode( $field ) );
	}

}