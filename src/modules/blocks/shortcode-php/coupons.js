const {__} = wp.i18n;
const {SelectControl} = wp.components;
const {Component} = wp.element;
const {apiFetch} = wp;

export default class SelectCoupons extends Component {

	state = {
		items: [],
		couponid: 0,
		item: {},
		loaded: false,
	}

	constructor() {
		super( ...arguments );
		console.log('props',this.props);
	}

	componentDidMount = () => {
		const {attributes: {couponid}} = this.props;
		this.setState( {couponid: couponid} );
		this.getOptions();
	}

	getOptions = () => {
		return (apiFetch( { path: this.props.fetchPath } ).then( ( items ) => {
			if ( items && (0 !== this.state.couponid || 'loop' !== this.state.couponid) ) {

				const item = items.find( ( item ) => {
					return item.id == this.state.couponid
				} );

				this.setState( {item, items} );
			} else {
				this.setState( {items} );
			}

			this.setState( {loaded: true} );
		} ));
	}

	onChangeSelectCoupon = ( value ) => {
		const {setAttributes} = this.props;

		setAttributes( {couponid: value} );
		this.setState( {couponid: value} );

	}

	render() {
		const {attributes: {couponid}} = this.props;
		let options = [{value: 0, label: __( 'Select a Coupon', 'coupon-creator' )}, {value: 'loop', label: __( 'All Coupons', 'coupon-creator' )}];
		let output = __( 'Loading', 'coupon-creator' );

		if ( this.state.items.length > 0 && this.state.loaded  ) {
			output = __( 'No coupons found. Please create some first.', 'coupon-creator' );
		} else {
			output = '';
		}

		return [
			(
				<SelectControl
					key="coupon-item-select"
					value={couponid}
					label={__( 'Select a Coupon', 'coupon-creator' )}
					options={options}
					onChange={this.onChangeSelectCoupon}
				/>
			),
			output
		]
	}
}