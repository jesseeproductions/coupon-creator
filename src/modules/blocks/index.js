import { registerBlockType } from "@wordpress/blocks";

import menuItem from  'blocks/menu-items';
import './style.pcss';

export default [
	menuItem,
];

this.default.forEach( block => {
	const blockName = `pngx/${block.id}`;
	registerBlockType( blockName, block );
} );
