<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       ibinex.com
 * @since      1.0.0
 *
 * @package    Ibinex_Crypto_Currency
 * @subpackage Ibinex_Crypto_Currency/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ibinex_Crypto_Currency
 * @subpackage Ibinex_Crypto_Currency/admin
 * @author     Jovi Torrevillas <jovi.to@ibinex.tech>
 */
class Ibinex_Crypto_Currency_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string    $plugin_name       The name of this plugin.
     * @param string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) 
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_styles() 
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ibinex_Crypto_Currency_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ibinex_Crypto_Currency_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ibinex-crypto-currency-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() 
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ibinex_Crypto_Currency_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ibinex_Crypto_Currency_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ibinex-crypto-currency-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Register the JavaScript for the add new and edit area of the
     * crypto CPT.
     *
     * @since    1.0.0
     */
    function add_crypto_cpt_enqueue( $hook ) {
        $check = ['crypto-display', 'crypto-calculator'];
        if( in_array($hook, array('post.php', 'post-new.php') ) ){
            $screen = get_current_screen();
            if( is_object( $screen ) && ( in_array($screen->post_type, $check)) ){
                wp_enqueue_script( 'crypto-meta/select2.js', plugin_dir_url(__FILE__) . 'js/select2.js', array('jquery') );
                wp_enqueue_style( 'crypto-meta/select2.css', plugin_dir_url(__FILE__) . 'css/select2.css', array() );
                wp_add_inline_script( 'crypto-meta/select2.js', 
                '(function($){
                    $(document).ready(function(){
                        $(".select-cryptocoin-multiple").select2({
                            placeholder: "Select cryptocurrencies",
                            maximumSelectionLength: 25,
                            allowClear: true
                        });
                    });
                })(jQuery);' );
                }
        }
    }

    /**
     * Registers the meta box that will be used to display all of the post meta data
     * associated with the current post.
     */
    public function add_crypto_meta_box() 
    {
        add_meta_box(
            'single-post-meta-manager-admin',
            'Single Post Meta Manager',
            array($this, 'crypto_meta_select_box'),
            ['crypto-display', 'crypto-calculator'],
            'normal',
            'core'
        );

    }

    /**
     * Saves the content of the meta box when the submit button is clicked.
     */
    public function save_crypto_meta_box( $post_id ) 
    {
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'crypto_symbols_nonce' ) ) return;
        
        // if our current user can't edit this post, bail
        if( !current_user_can( 'edit_post', $post_id ) ) return;
        
        // Make sure your data is set before trying to save it
        if( isset( $_POST['crypto_symbols'] ) )
            update_post_meta( $post_id, 'crypto_symbols', implode( ',', $_POST['crypto_symbols'] )  );
    }

    /**
     * Requires the file that is used to display the user interface of the post meta box.
     */
    public function crypto_meta_select_box( $meta_id ) 
    {
        global $post;
        $values = get_post_custom( $post->ID );
        wp_nonce_field( 'crypto_symbols_nonce', 'meta_box_nonce' );
        require_once plugin_dir_path(__FILE__) . 'partials/crypto_meta_select_box.php';
        
        /**
         * If it is not a new post, add the value a localized script.
         */
        
        if( array_key_exists("crypto_symbols", $values) )
            wp_localize_script('crypto-meta/select2.js', 'cryptoSymbols', $values['crypto_symbols']);
    }

}
