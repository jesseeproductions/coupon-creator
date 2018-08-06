
import React from 'react';
import { unescape } from 'lodash';
import { stringify } from 'querystringify';

const {__} = wp.i18n;
const {Component, compose } = wp.element;
const {
	SelectControl,
	CategorySelect,
	Spinner,
} = wp.components;
const {apiFetch} = wp;

export default class TaxonomiesSelect extends Component {
//export default function TaxonomiesSelect( {taxonomy, setAttributes, value} ) {

	constructor() {
		super( ...arguments );

		this.state = {
			terms: null,
		};
	}

	getTerms = ( parentId = null ) => {
		if ( ! this.props.terms ) {
			return [];
		}

		const terms = this.props.terms.data;
		if ( ! terms || ! terms.length ) {
			return [];
		}

		if ( parentId === null ) {
			return terms;
		}

		return terms.filter( ( term ) => term.parent === parentId );
	}

	getTermListClassName = ( level ) => (
		`pngx-editor__terms__list pngx-editor__terms__list--level-${ level }`
	);

	getTermListItemClassName = ( level ) => (
		`pngx-editor__terms__list-item pngx-editor__terms__list-item--level-${ level }`
	);

	renderTermName = ( term ) => {
		if ( ! term.name ) {
			return __( '(Untitled)', 'events-gutenberg' );
		}

		return unescape( term.name ).trim();
	}
	
	renderTermList() {
		const terms = this.getTerms( null );

		return (
			<ul className={ this.getTermListClassName( 0 ) }>
				{ terms.map( ( term, index ) => (
					this.renderTermListItem( term, index + 1 === terms.length, 0 )
				) ) }
			</ul>
		);
	}

	renderTermListItem = ( term, isLast, level ) => {
		const childTerms = this.getTerms( term.id );
		const separator = ! isLast ? (
			<span>
				{ this.props.termSeparator }
			</span>
		) : null;

		return (
			<li key={ term.id } className={ this.getTermListItemClassName( 0 ) }>
				<a
					href={ term.link }
					target="_blank"
					rel="noopener noreferrer"
					className="pngx-editor__terms__list-item-link"
				>
					{ this.renderTermName( term ) }
				</a>
				{ separator }
			</li>
		);
	}

	render() {
		const { attributes: { category }, setAttributes } = this.props;
		const terms = this.getTerms();
		const key = `pngx-terms-`;
		console.log( 'TaxonomiesSelect', category, terms );

		if ( this.props.terms.isLoading ) {
			return [
				<div key={ key } className={ `pngx-editor__terms` }>
					Loading Terms...
				</div>,
			];
		} else if ( ! terms.length ) {
			return (
				<div>
					No Terms
				</div>
			)
		}

		return (
			<div>
				This will be dropdown.
			</div>
			/*		<SelectControl
						key="coupon-taxomony-select"
						label={__( 'Coupon Category', 'coupon-creator' )}
						value={value || ''}
						options={[
							{value: 'cctor_alignnone', label: __( 'None', 'coupon-creator' )},

						]}
						onChange={value => setAttributes( {value} )}
					/>*/
		);
	}
}

async function getTaxonomy() {

	const response = await apiFetch( {path: '/wp/v2/categories?per_page=100'} );

	//console.log( 'getTaxonomyomies', response );

	//return response.json();
	return response;
}