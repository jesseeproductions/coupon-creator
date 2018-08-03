const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	InspectorControls,
} = wp.editor;
const {
	PanelBody,
	TextControl,
} = wp.components;

export default class Inspector extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const { attributes: { couponid }, setAttributes } = this.props;

		return (
			<InspectorControls>
				<PanelBody>
					<TextControl
						key="coupon-id2"
						label={ __( 'Coupon ID', 'coupon-creator' ) }
						help={ __( 'Add the coupon id to display.', 'coupon-creator' ) }
						value={ couponid || '' }
						onChange={ couponid => setAttributes( { couponid } ) }
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}
