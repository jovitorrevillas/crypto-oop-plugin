<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       ibinex.com
 * @since      1.0.0
 *
 * @package    Ibinex_Crypto_Currency
 * @subpackage Ibinex_Crypto_Currency/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ibinex_Crypto_Currency
 * @subpackage Ibinex_Crypto_Currency/includes
 * @author     Jovi Torrevillas <jovi.to@ibinex.tech>
 */
class Ibinex_Crypto_Currency {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Ibinex_Crypto_Currency_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
            $this->version = PLUGIN_NAME_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'ibinex-crypto-currency';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Ibinex_Crypto_Currency_Loader. Orchestrates the hooks of the plugin.
     * - Ibinex_Crypto_Currency_i18n. Defines internationalization functionality.
     * - Ibinex_Crypto_Currency_Admin. Defines all hooks for the admin area.
     * - Ibinex_Crypto_Currency_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for handling all the actions of the
         * Cryptocompare API
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ibinex-crypto-currency-api-handler.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ibinex-crypto-currency-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ibinex-crypto-currency-i18n.php';

        /**
         * The class responsible for registering a custom post type.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ibinex-crypto-currency-custom-post-type.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ibinex-crypto-currency-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ibinex-crypto-currency-public.php';
        
        $this->loader = new Ibinex_Crypto_Currency_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Ibinex_Crypto_Currency_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Ibinex_Crypto_Currency_i18n();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        // Create menu and attach the post type.
        $this->loader->add_action( 'admin_menu', $this, 'create_menu' );
        $this->loader->add_action( 'init', $this, 'load_cpt' );

        // Instantiate admin specific functions.
        $plugin_admin = new Ibinex_Crypto_Currency_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'add_crypto_cpt_enqueue' );
        
        
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_crypto_meta_box' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'save_crypto_meta_box' );


    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Ibinex_Crypto_Currency_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Ibinex_Crypto_Currency_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * This function introduces the top-level 'Sandbox Theme' menu.
     */
    public function create_menu() {
        // Create the page crypto_options
        add_menu_page(
            'Ibinex Cryptocurrency', 					// The title to be displayed in the browser window for this page.
            'Cryptocurrency',					    // The text to be displayed for this menu item
            'administrator',				    	// Which type of users can see this menu item
            'crypto_options',			            // The unique ID - that is, the slug - for this menu item
            '',                                     //The function to be called to output the content for this page
            'dashicons-list-view',                                     //The URL to the icon to be used for this menu
            '50'
        );
    }

    /**
     * Registers and enqueues the code responsible for automatically expanding the
     * excerpt field in the post editor.
     *
     * Note that the init hook must be used (rather than, say, plugins_loaded)
     * because trying to register a custom post type with a custom activation
     * throws an add_rewrite_tag() error.
     *
     * @since 1.0.0
     */
    public function load_cpt() {
        // Instantiate the custom post type class
        $display = new Crypto_Custom_Post_type();

        $args = array(
            'public'      => true,
            'show_ui'     => 'crypto_options',
            'supports'    => array(
                'title'
            ),
        );
        //Pass the arguments array for the crypto-display post type.
        $display->set_label( 'Cryptocurrency Display' );
        $display->set_slug( 'crypto-display' );
        $display->init( $args );
        
        //Pass the arguments array for the crypto-calculator post type.
        $display->set_label( 'Cryptocurrency Calculator' );
        $display->set_slug( 'crypto-calculator' );
        $display->init( $args );
    }

}
