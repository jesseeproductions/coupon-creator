const attributes = {
	couponid: {
		type: 'string',
		default: '0',
	},
	category: {
		type: 'array',
		items: {
			type: 'string',
		}
	},
	coupon_align: {
		type: 'string',
	},
	couponorderby: {
		type: 'string',
	},
};

export default attributes;
