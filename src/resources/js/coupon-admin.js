/**
 * Init Tabs
 */
(function ( $ ) {

	//Init Main Tabs
	var pngx_main_tabs = pngx_main_tabs || {};
	var main_tabs = new Pngx_Admin_Tabs( jQuery, pngx_main_tabs );
	main_tabs.init( '.main.pngx-tabs' );

})( jQuery );