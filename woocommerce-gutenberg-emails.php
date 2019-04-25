<?php
/**
 * Plugin Name: WooCommerce Gutenberg Emails
 * Plugin URI: https://github.com/woocommerce/woocommerce-gutenberg-emails
 * Description: A feature plugin to enable Gutenberg for editing emails.
 * Author: WooCommerce
 * Author URI: https://woocommerce.com/
 * Text Domain: woocommerce-gutenberg-emails
 * Domain Path: /languages
 * Version: 0.1.0
 *
 * WC requires at least: 3.5.0
 * WC tested up to: 3.5.7
 *
 * @package WC_Gutenberg_Emails
 */

if ( ! defined( 'WC_GUTENBERG_EMAILS_ABSPATH' ) ) {
	define( 'WC_GUTENBERG_EMAILS_ABSPATH', dirname( __FILE__ ) . '/' );
}

if ( ! defined( 'WC_GUTENBERG_EMAILS_PLUGIN_FILE' ) ) {
	define( 'WC_GUTENBERG_EMAILS_PLUGIN_FILE', __FILE__ );
}

/**
 * Calls the loader method
 *
 * @see WC_Gutenberg_Emails_Loader::__construct()
 */
function wc_gutenberg_emails_initialize() {
	require_once WC_GUTENBERG_EMAILS_ABSPATH . 'includes/class-wc-gutenberg-emails-loader.php';
}
add_action( 'woocommerce_loaded', 'wc_gutenberg_emails_initialize' );
