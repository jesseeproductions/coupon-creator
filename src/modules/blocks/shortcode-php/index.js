// License: GPLv2+

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const { InspectorControls } = wp.editor;
const { TextControl, ServerSideRender, } = wp.components;
const { Component, RawHTML } = wp.element;

var el = wp.element.createElement;


/**
 * Module Code
 */
export default {
	title: __( 'Coupon Creator PHP Shortcode' ),
	icon: 'index-card',
	category: 'widgets',

	edit: function( props ) {
		return [

			el( ServerSideRender, {
				block: 'pngx/coupon-shortcode-php',
				attributes: props.attributes,
			} ),

			el( InspectorControls, {},
				el( TextControl, {
					label: 'Coupon ID',
					value: props.attributes.couponid,
					onChange: ( value ) => { props.setAttributes( { couponid: value } ); },
				} )
			),
		];
	},

	save: function() {
		return null;
	},
}