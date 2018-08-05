

const {__} = wp.i18n;
const {
	SelectControl,
} = wp.components;
const {
	apiRequest,
} = wp.apiRequest;

export default function TaxonomiesSelect ({ taxonomy, setAttributes, value }) {

	console.log('taxonomies', taxonomy, value );
	//const categories = apiRequest( { path: '/wp/v2/categories?per_page=-1' } );
	//getCategories();

	return (
		<SelectControl
			label={__( 'Select a Coupon Category to use in the Loop', 'coupon-creator' )}
			value={value}
			options={[
				{value: 'cctor_alignnone', label: __( 'None', 'coupon-creator' )},
			]}
			onChange={value => setAttributes( {value} )}
		/>
	);
}

function getCategories() {
	apiRequest( { path: '/wp/v2/posts' } ).then( posts => {
	    console.log( posts );
	} );
	//const categories = await apiRequest( { path: '/wp/v2/categories?per_page=-1' } );
	//return ( categories );
	//yield receiveTerms( 'categories', categories );
}