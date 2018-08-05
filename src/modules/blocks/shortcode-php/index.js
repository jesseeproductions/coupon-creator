// License: GPLv2+

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import attributes from './attributes';
import Inspector from './inspector';

const {
	ServerSideRender,
} = wp.components;

/**
 * Module Code
 */
export default {
	id: 'coupon-shortcode-php',
	title: __( 'Coupon Creator PHP Shortcode', 'coupon-creator' ),
	description: __( 'Display coupons using the shortcode.', 'coupon-creator' ),
	icon: 'index-card',
	category: 'widgets',
	keywords: [ 'coupon', 'coupon creator', 'deal' ],

	attributes,

	edit: ( props ) => {
		const { attributes, setAttributes } = props;

		return [
			<Inspector
				{ ...{ setAttributes, ...props } }
			/>,
			<ServerSideRender
				key="coupon-render"
				block="pngx/coupon-shortcode-php"
				attributes={ attributes }
			/>,
		];
	},

	save() {
		return null;
	},
};
