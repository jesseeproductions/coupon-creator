/**
 * External dependencies
 */
import React from 'react';

/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './style.pcss';

/**
 * Module Code
 */

const Upgrade = () => (
	<div className="pngx-message pngx-warning">
		<p className="pngx-message-upgrade-text">
			<a
				href="http://cctor.link/Abqoi"
				className="pngx-message-upgrade-link"
				target="_blank"
				rel="noopener noreferrer"
			>
				{__( 'Upgrade to Pro', 'coupon-creator' )}
			</a>
			{__(
				` for the expanded Coupon Loop with Filter Bar! `,
				'coupon-creator'
			)}
		</p>
	</div>
);

export default Upgrade;