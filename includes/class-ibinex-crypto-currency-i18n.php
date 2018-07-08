<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       ibinex.com
 * @since      1.0.0
 *
 * @package    Ibinex_Crypto_Currency
 * @subpackage Ibinex_Crypto_Currency/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ibinex_Crypto_Currency
 * @subpackage Ibinex_Crypto_Currency/includes
 * @author     Jovi Torrevillas <jovi.to@ibinex.tech>
 */
class Ibinex_Crypto_Currency_i18n {


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'ibinex-crypto-currency',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }



}
