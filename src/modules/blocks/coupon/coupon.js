/**
 * Internal block libraries
 */
const {__} = wp.i18n;
const {Component} = wp.element;
import {RESTSelect} from 'elements';

export default class CouponChooser extends Component {

	constructor() {
		super( ...arguments );
	}

	render() {
		const {attributes: {couponid}, className, setAttributes} = this.props;

		return (
			<div className="pngx-message">
				<RESTSelect
					{...{setAttributes}}
					attributesID="couponid"
					currentId={couponid}
					defaultOptions={[
						{value: '0', label: __( 'Select a Coupon', 'coupon-creator' )},
						{value: 'loop', label: __( 'All Coupons', 'coupon-creator' )},
					]}
					fetchPath="/wp/v2/cctor_coupon?per_page=100"
					label={__( 'Select a Coupon', 'coupon-creator' )}
					noItems={__( 'No coupons found. Please create some first.', 'coupon-creator' )}
					slug="coupon-item-select"
				/>
			</div>
		);
	}
}
