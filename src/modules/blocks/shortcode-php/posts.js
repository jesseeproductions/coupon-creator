const {__} = wp.i18n; // Import __() from wp.i18n
const {registerBlockType, InspectorControls} = wp.blocks; // Import registerBlockType() from wp.blocks
const {SelectControl} = wp.components;
const {Component} = wp.element;

export default class SelectPosts extends Component {

	// Method for setting the initial state.
	static getInitialState( selectedPost ) {
		return {
			posts: [],
			selectedPost: selectedPost,
			post: {},
		};
	}

	// Constructing our component. With super() we are setting everything to 'this'.
	// Now we can access the attributes with this.props.attributes
	constructor() {
		super( ...arguments );
		this.state = this.constructor.getInitialState( this.props.attributes.selectedPost );
		// Bind so we can use 'this' inside the method.
		this.getOptions = this.getOptions.bind( this );
		this.onChangeSelectPost = this.onChangeSelectPost.bind( this );
		// Load posts.
		this.getOptions();
	}

	/**
	 * Loading Posts
	 */
	getOptions() {
		return (new wp.api.collections.Posts()).fetch().then( ( posts ) => {
			if ( posts && 0 !== this.state.selectedPost ) {
				// If we have a selected Post, find that post and add it.
				const post = posts.find( ( item ) => {
					return item.id == this.state.selectedPost
				} );
				// This is the same as { post: post, posts: posts }
				this.setState( {post, posts} );
			} else {
				this.setState( {posts} );
			}
		} );
	}

	onChangeSelectPost( value ) {
		// Find the post
		const post = this.state.posts.find( ( item ) => {
			return item.id == parseInt( value )
		} );
		// Set the state
		this.setState( {selectedPost: parseInt( value ), post} );
		// Set the attributes
		this.props.setAttributes( {
			selectedPost: parseInt( value ),
			title: post.title.rendered,
			content: post.excerpt.rendered,
			link: post.link,
		} );
	}

	render() {
		// Options to hold all loaded posts. For now, just the default.
		let options = [{value: 0, label: __( 'Select a Post' )}];
		let output = __( 'Loading Posts' );
		if ( this.state.posts.length > 0 ) {
			const loading = __( 'We have %d posts. Choose one.' );
			output = loading.replace( '%d', this.state.posts.length );
			this.state.posts.forEach( ( post ) => {
				options.push( {value: post.id, label: post.title.rendered} );
			} );
		} else {
			output = __( 'No posts found. Please create some first.' );
		}
		return [
			(
				<SelectControl
					key="coupon-post-select"
					value={this.props.attributes.selectedPost}
					label={__( 'Select a Post' )}
					options={options}
					onChange={this.onChangeSelectPost}
				/>
			)
		]
	}
}