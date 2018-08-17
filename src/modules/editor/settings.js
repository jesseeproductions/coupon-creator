/**
 * External dependencies
 */
import { get, has } from 'lodash';

export const getConstants = () => (
	window.pngx_blocks_editor_settings.constants || {}
);
