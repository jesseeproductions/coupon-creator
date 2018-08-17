import { registerBlockType } from '@wordpress/blocks';

import coupon from 'blocks/coupon';
import './style.pcss';

const blocks = [
	coupon,
];

blocks.forEach( block => {
	const blockName = `pngx/${ block.id }`;
	registerBlockType( blockName, block );
} );

export default blocks;
