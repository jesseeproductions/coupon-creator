const {__} = wp.i18n;
const {
	SelectControl,
	CategorySelect,
} = wp.components;
const {apiFetch} = wp;

export default function TaxonomiesSelect( {taxonomy, setAttributes, value} ) {

	console.log( 'taxonomies', taxonomy, value );
	const response = getTaxonomy();
	let categories = {};

	//try a reducer?
	{response.map(({ name, id }, index) => (
		console.log(name, id),
		{value: id, label: name }
    ))}

	return (
		<SelectControl
			key="coupon-taxomony-select"
			label={__( 'Coupon Category', 'coupon-creator' )}
			value={value || ''}
			options={[
				{value: 'cctor_alignnone', label: __( 'None', 'coupon-creator' )},

			]}
			onChange={value => setAttributes( {value} )}
		/>
	)

}

async function getTaxonomy() {

	const response = await apiFetch( {path: '/wp/v2/categories?per_page=100'} );

	//console.log( 'getTaxonomyomies', response );

	//return response.json();
	return response;
}