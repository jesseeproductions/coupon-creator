/**
 * Init Tabs
 */
(function ( $ ) {

	//Init Main Tabs
	//pngx_admin_tabs.init( '.main.pngx-tabs' );

	var main_tabs;
	$( window ).on( 'load', function ( e ) {
		console.log( 'here' );
		main_tabs = pngx_admin_tabs;
		main_tabs.init( '.main.pngx-tabs' );
	} );

	//Create Image Upload Object
	var coupon_img = new PNGX__Media( $, 'input#cctor_image', 'Choose Coupon Image', 'Use Image' );

})( jQuery );