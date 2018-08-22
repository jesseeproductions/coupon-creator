// License: GPLv2+

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';
import attributes from './attributes';
import Inspector from './inspector';
import CouponChooser from './coupon';

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
	keywords: ['coupon', 'coupon creator', 'deal'],

	attributes,

	edit: ( props ) => {
		const {attributes: {couponid}, attributes, className, setAttributes} = props;

		let display;
		if ( couponid === '0' ) {
			display = (
				<CouponChooser {...{setAttributes, ...props}} />
			);
		} else {
			display = (
				<ServerSideRender
					key="coupon-render"
					block="pngx/coupon"
					attributes={attributes}
				/>
			);
		}

		return [
			<Inspector
				key="coupon-inspector"
				{...{setAttributes, ...props}}
			/>,
			<div key="coupon-base" className={`${className} pngx-clearfix`}>
				{display}
			</div>,
		];
	},

	save() {
		return null;
	},
};
