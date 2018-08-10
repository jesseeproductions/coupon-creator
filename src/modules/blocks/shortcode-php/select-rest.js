const {__} = wp.i18n;
const {SelectControl} = wp.components;
const {Component} = wp.element;
const {apiFetch} = wp;

export default class PngxRESTSelect extends Component {

	/**
	 * pass in all data from props
	 * default options
	 * get options special cases
	 * different titles and names for category and coupons
	 * all text
	 */

	state = {
		defaultOptions: [],
		item: {},
		items: [],
		loaded: false,
		selectedId: 0,
	}

	constructor() {
		super( ...arguments );
		console.log('options1');
	}

	componentDidMount = () => {
		const {ID, defaultOptions} = this.props;
		console.log('options2',defaultOptions);
		this.setState( {selectedId: ID} );
		this.setState( {defaultOptions: defaultOptions} );
		this.getOptions();
	}

	getOptions = () => {
		const {fetchPath} = this.props;

		return (apiFetch( {path: fetchPath} ).then( ( items ) => {
			if ( items && (0 !== this.state.selectedId || 'loop' !== this.state.selectedId) ) {

				const item = items.find( ( item ) => {
					return item.id == this.state.selectedId
				} );
				console.log('items1',items);
				this.setState( {item, items} );
			} else {
				console.log('items2',items);
				this.setState( {items} );
			}

			this.setState( {loaded: true} );
		} ));
	}

	onChangeSelectCoupon = ( value ) => {
		const {attributesID, setAttributes} = this.props;

		setAttributes( {[attributesID]: value} );
		this.setState( {selectedId: value} );
	}

	render() {
		const {ID} = this.props;
		let options = this.state.defaultOptions;
		let output = __( 'Loading', 'coupon-creator' );

		if ( this.state.items.length === 0 && this.state.loaded ) {
			output = __( 'No coupons found. Please create some first.', 'coupon-creator' );
		} else if ( this.state.items.length > 0 && this.state.loaded ) {
			console.log('options3', options);
			this.state.items.forEach( ( item ) => {
				options.push( {value: item.id, label: item.title.rendered} );
			} );
			output = '';
		}

		return [
			(
				<SelectControl
					key="coupon-item-select"
					value={ID}
					label={__( 'Select a Coupon', 'coupon-creator' )}
					options={options}
					onChange={this.onChangeSelectCoupon}
				/>
			),
			output
		]
	}
}