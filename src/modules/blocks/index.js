import { registerBlockType } from '@wordpress/blocks';

import couponShortcodePhp from 'blocks/shortcode-php';
import './style.pcss';

const blocks = [
	couponShortcodePhp,
];

blocks.forEach( block => {
	const blockName = `pngx/${ block.id }`;
	registerBlockType( blockName, block );
} );

export default blocks;