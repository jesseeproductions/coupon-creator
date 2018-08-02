import { registerBlockType } from '@wordpress/blocks';

import couponShortcode from  'blocks/shortcode';
import couponShortcodePhp from  'blocks/shortcode-php';
import './style.pcss';

const blocks = [
	//couponShortcode,
	couponShortcodePhp,
];

blocks.forEach( block => {
	const blockName = `pngx/${ block.id }`;
	registerBlockType( blockName, block );
} );

export default blocks;