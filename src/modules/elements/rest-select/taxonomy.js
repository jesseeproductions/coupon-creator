const { __ } = wp.i18n;
const { SelectControl } = wp.components;
const { Component } = wp.element;
const { apiFetch } = wp;

export default class SelectTaxonomy extends Component {
	// Method for setting the initial state.
	static getInitialState( selectedTaxonomy ) {
		return {
			terms: [],
			selectedTaxonomy: selectedTaxonomy,
			term: {},
		};
	}

	// Constructing our component. With super() we are setting everything to 'this'.
	// Now we can access the attributes with this.props.attributes
	constructor() {
		super( ...arguments );
		this.state = this.constructor.getInitialState( this.props.attributes.selectedTaxonomy );
		// Bind so we can use 'this' inside the method.
		this.getOptions = this.getOptions.bind( this );
		this.onChangeSelectTerm = this.onChangeSelectTerm.bind( this );
		// Load terms.
		this.getOptions();
	}

	/**
	 * Loading terms
	 */
	getOptions() {
		return ( apiFetch( { path: '/wp/v2/categories?per_page=100' } ).then( ( terms ) => {
			//console.log('fetch',terms);
			if ( terms && 0 !== this.state.selectedTaxonomy ) {
				// If we have a selected Term, find that term and add it.
				const term = terms.find( ( item ) => {
					return item.id == this.state.selectedTaxonomy;
				} );
				// This is the same as { term: term, terms: terms }
				this.setState( { term, terms } );
			} else {
				//console.log('adding',terms);
				this.setState( { terms } );
			}
		} ) );
	}

	onChangeSelectTerm( value ) {
		// Find the term
		const term = this.state.terms.find( ( item ) => {
			return item.id == parseInt( value );
		} );
		// Set the state
		this.setState( { selectedTaxonomy: parseInt( value ), term } );
		// Set the attributes
		this.props.setAttributes( {
			selectedTaxonomy: parseInt( value ),
			name: term.name,
		} );
		//onChange={couponid => setAttributes( {couponid} )}
	}

	render() {
		// Options to hold all loaded terms. For now, just the default.
		const options = [{ value: 0, label: __( 'Select a Term' ) }];
		let output = __( 'Loading Terms' );
		if ( this.state.terms.length > 0 ) {
			const loading = __( 'We have %d terms. Choose one.' );
			output = loading.replace( '%d', this.state.terms.length );
			this.state.terms.forEach( ( term ) => {
				options.push( { value: term.id, label: term.name } );
			} );
		} else {
			output = __( 'No terms found. Please create some first.' );
		}
		return [
			(
				<SelectControl
					key="coupon-term-select"
					value={ this.props.attributes.selectedTaxonomy }
					label={ __( 'Select a Term' ) }
					options={ options }
					onChange={ this.onChangeSelectTerm }
				/>
			),
		];
	}
}
