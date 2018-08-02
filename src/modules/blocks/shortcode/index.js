/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const { InspectorControls } = wp.blocks;
const { Dashicon, Placeholder, SelectControl, Spinner } = wp.components;
const { Component, RawHTML } = wp.element;

import { addQueryArgs } from '@wordpress/url';
import SandBox from '../../components/sandbox/';


/**
 * Module Code
 */
export default {
	title: __( 'Coupon Creator Shortcode' ),
	icon: 'index-card',
	category: 'widgets',
	supports:    {
		customClassName: false,
		className:       false,
		html:            false,
	},
	attributes: {
		couponId:           {
			type: 'integer',
		},
	},

	transforms: {
		from: [
			{
				type:       'shortcode',
				tag:        [ 'coupon-creator', 'coupons' ],
				attributes: {
					couponId:      {
						type:      'string',
						shortcode: ( { named: { id } } ) => {
							return parseInt( id );
						},
					},
				},
			}
		]
	},

	edit: class extends Component {

		constructor() {

			super( ...arguments );

			this.setcouponId = this.setcouponId.bind( this );
			this.updatecouponPreview = this.updatecouponPreview.bind( this );

			this.state = {
				html:         '',
				fetching:     false,
				previewError: false,
			}

		}

		componentWillMount() {

			let { couponId, couponPreview } = this.props.attributes;
			let formFound = false;

			if ( ! couponId ) {
				return;
			}

			for ( let i = 0; i < pngx.coupons.length; i ++ ) {
				if ( pngx.coupons[ i ].value == couponId ) {
					formFound = true;
				}
			}

			if ( ! formFound ) {
				this.props.setAttributes( { couponId: '' } );
				return;
			}

			if ( this.props.attributes.couponId && this.props.attributes.couponPreview ) {
				this.setState( { fetching: true } );
				this.updatecouponPreview( this.props.attributes );
			}

		}

		componentWillReceiveProps( props ) {

			let oldAtts = this.props.attributes,
				newAtts = props.attributes;

			if ( oldAtts.couponId === newAtts.couponId && oldAtts.title === newAtts.title && oldAtts.description === newAtts.description && oldAtts.couponPreview === newAtts.couponPreview ) {
				return;
			}

			if ( ! props.attributes.couponId ) {
				this.setState( { html: '' } );
				return;
			}

			this.updatecouponPreview( props.attributes );

		}

		componentWillUnmount() {

			this.unmounting = true;

		}

		setcouponId( couponId ) {

			this.props.setAttributes( { couponId: couponId } );

		}

		updatecouponPreview( atts ) {

			if ( this.state.fetching || ! atts.couponPreview ) {
				return;
			}

			const { couponId, title } = atts;

			const apiURL = addQueryArgs( wpApiSettings.root + 'gf/v2/block/preview', {
				couponId:      couponId,
				title:       title ? title : false,
				type:        'gravityforms/block'
			} );

			this.setState( { fetching: true } );

			window.fetch( apiURL ).then(
				( response ) => {

					if ( this.unmounting ) {
						return;
					}

					response.json().catch( ( error ) => {
						return { success: false };
					} ).then( ( obj ) => {

						if ( obj.success ) {
							this.setState( {
								fetching:     false,
								html:         obj.data.html,
								previewError: false
							} );
						} else {
							this.setState( {
								fetching:     false,
								previewError: true
							} );
						}

					} );

				},
			);

		}

		render = () => {

			let { couponId,couponPreview } = this.props.attributes;

			const { html, fetching, previewError } = this.state;
			const { setAttributes, isSelected } = this.props;
			const setcouponIdFromPlaceholder = ( e ) => this.setcouponId( e.target.value );

			const controls = [
				isSelected && (
					<InspectorControls key="inspector">
						<SelectControl
							label={ __( 'Coupon', 'coupon-creator' ) }
							value={ couponId }
							options={ pngx.coupons }
							onChange={ this.setcouponId }
						/>
					</InspectorControls>
				),
			];

			if ( fetching ) {
				return [
					controls,
					<div key="loading" className="wp-block-embed is-loading">
						<Spinner/>
						<p>{ __( 'Loading form preview...', 'coupon-creator' ) }</p>
					</div>,
				];
			}

			if ( previewError ) {
				return [
					controls,
					<div key="loading" className="wp-block-embed is-loading">
						<Dashicon icon="dismiss"/>
						<p>{ __( 'Could not load coupon preview.', 'coupon-creator' ) }</p>
					</div>,
				];
			}

			if ( ! html || ! couponPreview ) {

				return [
					controls,
					<Placeholder key="placeholder" className="wp-block-embed pngx-block__placeholder">
						<div className="pngx-block__placeholder-brand">
							<p><strong>Coupon Creator</strong></p>
						</div>
						<form>
							<select value={ couponId } onChange={ setcouponIdFromPlaceholder }>
								{ pngx.coupons.map( coupon =>
									<option key={ coupon.value } value={ coupon.value }>{ coupon.label }</option>,
								) }
							</select>
						</form>
					</Placeholder>,
				];

			}

			return [
				controls,
				<div key="sandbox" className="wp-block-embed__wrapper">
					<SandBox html={ html }/>
				</div>,
			];

		}

	},

	save: function ( props ) {

		let { couponId } = props.attributes;
		let shortcode = `[coupon couponid="${couponId}" ]`;

		return couponId ? <RawHTML>{ shortcode }</RawHTML> : null;

	},
};