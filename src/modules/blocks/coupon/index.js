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
	id: 'coupon',
	title: __( 'Coupon Creator', 'coupon-creator' ),
	description: __( 'Display a single or group of coupons.', 'coupon-creator' ),
	icon: 'index-card',
	category: 'widgets',
	keywords: [ 'coupon', 'coupon creator', 'deal' ],

	attributes,

	edit: ( props ) => {
		const { attributes, className, setAttributes } = props;

		return [
			<Inspector
				key="coupon-inspector"
				{ ...{ setAttributes, ...props } }
			/>,
			<div key="coupon-base" className={ `${className} pngx-clearfix` } >
				<ServerSideRender
					key="coupon-render"
					block="pngx/coupon"
					attributes={ attributes }
				/>
			</div>,
		];
	},

	save() {
		return null;
	},
};
