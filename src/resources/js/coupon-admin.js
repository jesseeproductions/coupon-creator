/**
 * Init Tabs
 */
(function ( $ ) {

	//var sections = jQuery.parseJSON( cctor_admin_js_vars.tabs_arr.replace( /&quot;/g, '"' ) );
	//var updated_tab = cctor_admin_js_vars.cctor_coupon_updated;
	//var coupon_id = cctor_admin_js_vars.cctor_coupon_id;
	pngx_admin_tabs.init( '.main.pngx-tabs' );

	//Create Image Upload Object
	var coupon_img = new PNGX__Media( $, 'input#cctor_image', 'Choose Coupon Image', 'Use Image' );

})( jQuery );