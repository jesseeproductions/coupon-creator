<?php
/**
 * Created by PhpStorm.
 * User: brianjessee
 * Date: 1/11/2016
 * Time: 9:08 AM
 */

echo $post;

?><h3 class="cctor_deal" style="background-color:<?php echo esc_attr(get_post_meta($coupon_id, 'cctor_colordiscount', true));  ?>; color:<?php echo esc_attr(get_post_meta($coupon_id, 'cctor_colorheader', true)); ?>;"><?php echo esc_html(get_post_meta($coupon_id, 'cctor_amount', true));  ?></h3><?php