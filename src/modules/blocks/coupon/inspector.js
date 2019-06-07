const {__} = wp.i18n;
const {Component} = wp.element;

const {
	InspectorControls,
} = wp.editor;

const {
	PanelRow,
	SelectControl,
} = wp.components;

import {getConstants} from 'editor/settings';
import {Upgrade, RESTSelect} from 'elements';
import CouponChooser from './coupon';

export default class Inspector extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const {attributes: {couponid, category, coupon_align, couponorderby}, setAttributes} = this.props;
		const hideUpgrade = getConstants().hide_upgrade === 'true';

		let taxonomy = '';
		if ( 'loop' === couponid ) {
			taxonomy = (
				<PanelRow>
					<RESTSelect
						{...{setAttributes}}
						attributesID="category"
						currentId={category}
						defaultOptions={[
							{value: 'none', label: __( 'No Categories', 'coupon-creator' )},
						]}
						fetchPath="/wp/v2/cctor_coupon_category?per_page=100&hide_empty=true"
						//fetchPath="/wp/v2/cctor_coupon_category?per_page=100"
						isMultiple={true}
						isTaxonomy={true}
						label={__( 'Select a Category', 'coupon-creator' )}
						noItems={__( 'No category terms found. Please create some first.', 'coupon-creator' )}
						slug="coupon-category-item-select"
					/>
				</PanelRow>
			);
		}
		let order = '';
		if ( 'loop' === couponid ) {
			order = (
			<PanelRow>
				<SelectControl
					key="coupon-order-select"
					label={__( 'Select how to order the coupons', 'coupon-creator' )}
					value={couponorderby || ''}
					options={[
						{value: 'date', label: __( 'Date (default)', 'coupon-creator' )},
						{value: 'none', label: __( 'None', 'coupon-creator' )},
						{value: 'ID', label: __( 'ID', 'coupon-creator' )},
						{value: 'author', label: __( 'Author', 'coupon-creator' )},
						{value: 'title', label: __( 'Coupon Post Title', 'coupon-creator' )},
						{value: 'name', label: __( 'Slug Name', 'coupon-creator' )},
						{value: 'modified', label: __( 'Last Modified', 'coupon-creator' )},
						{value: 'rand', label: __( 'Random', 'coupon-creator' )},
					]}
					onChange={couponorderby => setAttributes( {couponorderby} )}
				/>
			</PanelRow>);
		}

		return (
			<InspectorControls>

				<PanelRow className="coupon-chooser">
					<CouponChooser {...{setAttributes, ...this.props}} />
				</PanelRow>

				{taxonomy}

				<PanelRow className="coupon-align-select" >
					<SelectControl
						key="coupon-align-select"
						label={__( 'Select How to Align the Coupon(s)', 'coupon-creator' )}
						value={coupon_align || ''}
						options={[
							{value: 'cctor_alignnone', label: __( 'None', 'coupon-creator' )},
							{value: 'cctor_alignleft', label: __( 'Align Left', 'coupon-creator' )},
							{value: 'cctor_alignright', label: __( 'Align Right', 'coupon-creator' )},
							{value: 'cctor_aligncenter', label: __( 'Align Center', 'coupon-creator' )},
						]}
						onChange={coupon_align => setAttributes( {coupon_align} )}
					/>
				</PanelRow>

				{order}

				{!hideUpgrade && <Upgrade/>}

			</InspectorControls>
		);
	}
}
