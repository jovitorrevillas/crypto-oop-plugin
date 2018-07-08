<?php
/**
 * The core class for registering a custom post type.
 *
 * @package Crypto_CPT
 * @since   1.0.0
 */
/**
 * Provides attributes and functionality specifically for creating a custom
 * post type to be used for testing other plugins that may relay on custom
 * features.
 *
 * @package Crypto_CPT
 * @since   1.0.0
 */
class Crypto_Custom_Post_Type {
	/**
	 * Maintains the current version of this plugin. Defined
	 * in the constructor.
	 *
	 * @access private
	 * @var    string
	 */
    private $version;
    
	/**
	 * Stores the slug for the post type. Defined
	 * in the constructor.
	 *
	 * @access private
	 * @var    string
	 */
    private $slug;
    
	/**
	 * Stores the label for the post type. Defined
	 * in the constructor.
	 *
	 * @access private
	 * @var    string
	 */
    private $label;
    
	/**
	 * Defines values for the class' attributes, namely its version, during
	 * instantiation.
	 */
	public function __construct() {
        $this->version = '1.0.0';
        $this->slug = '';
        $this->label = '';
    }
    
	/**
	 * Provides a function to the `init` hook for registering a custom post type.
	 *
	 * @param array $args The arguments for the custom post type.
	 */
	public function init( $args ) {
		// Define a custom action that will pass the $args to the generic post type.
		add_action(
			'register_crypto_post_type',
			array( $this, 'register_post_type' ),
			10,
			1
		);
		// Perform the custom action passing the arguments.
		do_action( 'register_crypto_post_type', $args );
    }
    
	/**
	 * Sets the slug.
	 *
	 * @param string $slug The name of the slug.
	 */
    public function set_slug( $slug ) {
        $this->slug = $slug;
    }
    
	/**
	 * Sets the label.
	 *
	 * @param string $slug The name of the slug.
	 */
    public function set_label( $label ) {
        $this->label = $label;
    }
    
	/**
	 * Defines a 'Generic Post' custom post type along with some basic features
	 * that will allow third-party plugins to test their functionality against
	 * a custom post type.
	 *
	 * @param array $args The arguments for the custom post type.
     * @param string $
	 */
	public function register_post_type( $args ) {
        $args['label'] = $this->label;
		register_post_type(
			$this->slug,
			$args
		);
	}
}