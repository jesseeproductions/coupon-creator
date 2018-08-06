const {__} = wp.i18n;
const {Component} = wp.element;
//import TaxonomiesSelect from './taxonomy';
import SelectPosts from './posts';

const {
	InspectorControls,
} = wp.editor;
const {
	PanelBody,
	TextControl,
	SelectControl,
} = wp.components;

export default class Inspector extends Component {

	constructor() {
		super( ...arguments );
	}

	render() {
		const {attributes: {couponid, category, coupon_align, couponorderby}, setAttributes} = this.props;
		let options = [{value: 0, label: __( 'Select a Post' )}];

		return (
			<InspectorControls>
				<PanelBody>
					<TextControl
						key="coupon-id"
						label={__( 'Coupon ID', 'coupon-creator' )}
						help={__( 'Add the coupon id to display.', 'coupon-creator' )}
						value={couponid || ''}
						onChange={couponid => setAttributes( {couponid} )}
					/>
				</PanelBody>

				<PanelBody>
					<SelectPosts { ...{ setAttributes, ...this.props } } />
					{/*					<TaxonomiesSelect
						slug="cctor_coupon_category"
						label={ __( 'Coupon Category', 'coupon-creator' ) }
						renderEmpty={ __( 'Add Coupon Categories in document settings', 'coupon-creator' ) }
					/>*/}
				</PanelBody>

				<PanelBody>
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
				</PanelBody>

				<PanelBody>
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
				</PanelBody>
			</InspectorControls>
		);
	}
}
