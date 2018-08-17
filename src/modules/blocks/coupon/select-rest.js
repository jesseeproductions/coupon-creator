const {__} = wp.i18n;
const {SelectControl} = wp.components;
const {Component} = wp.element;
const {apiFetch} = wp;
import {Loading} from 'elements';

export default class PngxRESTSelect extends Component {

	state = {
		defaultOptions: [],
		loadedItems: [],
		loaded: false,
		options: [],
		isMultiple: false,
		isTaxonomy: false,
	}

	constructor() {
		super( ...arguments );
	}

	componentDidMount = () => {
		const {defaultOptions, isMultiple, isTaxonomy} = this.props;

		this.setState( {defaultOptions, isMultiple, isTaxonomy} );
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
				options.push( {value: term.slug, label: term.name} );
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
	}

	render() {
		const {currentId, label, noItems, slug} = this.props;
		let output = <Loading key="pngx-select-loading-{slug}" className="pngx-editor__spinner--item"/>;
		let select = '';

		if ( this.state.loadedItems.length === 0 && this.state.loaded ) {
			output = {noItems};
		} else if ( this.state.loadedItems.length > 0 && this.state.loaded ) {
			output = '';
			let value = this.state.isMultiple && ! Array.isArray( currentId ) ? [] : currentId;
			select = (
				<SelectControl
					multiple={this.state.isMultiple}
					key={slug}
					value={value}
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
