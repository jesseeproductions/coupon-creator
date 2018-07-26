( function( blocks, i18n, element, _ ) {
	var el = element.createElement;
	var children = blocks.source.children;
	var attr = blocks.source.attr;

	blocks.registerBlockType( 'menu-crafter/item', {
		title: i18n.__( 'Menu Item' ),
		icon: 'index-card',
		category: 'layout',
		attributes: {
			title: {
				type: 'array',
				source: 'children',
				selector: 'h2',
			},
			mediaID: {
				type: 'number',
			},
			price: {
				type: 'array',
				source: 'children',
				selector: '.price',
			},
			description: {
				type: 'array',
				source: 'children',
				selector: '.description',
			},
		},
		edit: function( props ) {
			var focusedEditable = props.focus ? props.focus.editable || 'title' : null;
			var attributes = props.attributes;

			return (
				el( 'div', { className: props.className },
					el( blocks.Editable, {
						tagName: 'h2',
						inline: true,
						placeholder: i18n.__( 'Write Item title…' ),
						value: attributes.title,
						onChange: function( value ) {
							props.setAttributes( { title: value } );
						},
						focus: focusedEditable === 'title' ? focus : null,
						onFocus: function( focus ) {
							props.setFocus( _.extend( {}, focus, { editable: 'title' } ) );
						},
					} ),
					el( 'h3', {}, i18n.__( 'Price' ) ),
					el( blocks.Editable, {
						tagName: 'ul',
						multiline: 'li',
						placeholder: i18n.__( 'Write out Prices…' ),
						value: attributes.price,
						onChange: function( value ) {
							props.setAttributes( { price: value } );
						},
						focus: focusedEditable === 'price' ? focus : null,
						onFocus: function( focus ) {
							props.setFocus( _.extend( {}, focus, { editable: 'price' } ) );
						},
						className: 'price',
					} ),
					el( 'h3', {}, i18n.__( 'Description' ) ),
					el( blocks.Editable, {
						tagName: 'div',
						inline: false,
						placeholder: i18n.__( 'Write description…' ),
						value: attributes.description,
						onChange: function( value ) {
							props.setAttributes( { description: value } );
						},
						focus: focusedEditable === 'description' ? focus : null,
						onFocus: function( focus ) {
							props.setFocus( _.extend( {}, focus, { editable: 'description' } ) );
						},
					} ),
				)
			);
		},
		save: function( props ) {
			var attributes = props.attributes;

			return (
				el( 'div', { className: props.className },
					el( 'h2', {}, attributes.title ),
					el( 'h3', {}, i18n.__( 'Price' ) ),
					el( 'ul', { className: 'price' }, attributes.price ),
					el( 'h3', {}, i18n.__( 'Description' ) ),
					el( 'div', { className: 'description' }, attributes.description ),
				)
			);
		},
	} );

} )(
	window.wp.blocks,
	window.wp.i18n,
	window.wp.element,
	window._,
);