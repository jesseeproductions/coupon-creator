

const {__} = wp.i18n;
const {Component} = wp.element;

export default class TaxonomiesSelect extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		console.log('taxonomies', this.props);
		//const { attributes, className, focus, setAttributes, slug } = this.props;
		return (
			<div>
				Taxonomy
			</div>
		/*	<SelectControl
				label={__( 'Select a Coupon Category to use in the Loop', 'coupon-creator' )}
				value={category || ''}
				options={[
					{value: 'cctor_alignnone', label: __( 'None', 'coupon-creator' )},
				]}
				onChange={category => setAttributes( {category} )}
			/>*/
		);
	}
}