const {__} = wp.i18n;
const {SelectControl} = wp.components;
const {Component} = wp.element;
const {apiFetch} = wp;

export default class PngxRESTSelect extends Component {

	state = {
		defaultOptions: [],
		loadedItems: [],
		loaded: false,
		options: [],
		selectedId: 0,
		isTaxonomy: false,
	}

	constructor() {
		super( ...arguments );
	}

	componentDidMount = () => {
		const {currentId, defaultOptions, isTaxonomy} = this.props;

		this.setState( {selectedId: currentId, defaultOptions, isTaxonomy} );
		this.getOptions();
	}

	getOptions = () => {
		const {fetchPath} = this.props;

		return (apiFetch( {path: fetchPath} ).then( ( loadedItems ) => {
			if ( loadedItems ) {
				this.setState( {loadedItems, loaded: true} );
				this.setOptions();
			}

		} ));
	}

	setOptions = () => {
		let options = this.state.defaultOptions;

		if ( this.state.isTaxonomy ) {
			this.state.loadedItems.forEach( ( term ) => {
				options.push( {value: term.id, label: term.name} );
			} );
		} else {
			this.state.loadedItems.forEach( ( item ) => {
				options.push( {value: item.id, label: item.title.rendered} );
			} );
		}

		this.setState( {options} );
	}

	onChangeSelectCoupon = ( value ) => {
		const {attributesID, setAttributes} = this.props;

		setAttributes( {[attributesID]: value} );
		this.setState( {selectedId: value} );
	}

	render() {
		const {currentId, label, noItems, slug} = this.props;
		let output = __( 'Loading', 'coupon-creator' );
		let select = '';

		if ( this.state.loadedItems.length === 0 && this.state.loaded ) {
			output = {noItems};
		} else if ( this.state.loadedItems.length > 0 && this.state.loaded ) {
			output = '';
			select = (
				<SelectControl
					key={slug}
					value={currentId}
					label={label}
					options={this.state.options}
					onChange={this.onChangeSelectCoupon}
				/>
			);
		}

		return [
			(select),
			output,
		];
	}
}
