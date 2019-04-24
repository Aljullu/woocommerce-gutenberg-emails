<?php
/**
 * Initializes WooCommerce Gutenberg Emails.
 *
 * @package Woocommerce Gutenberg Emails
 */

/**
 * WC_Gutenberg_Emails_Loader Class.
 */
class WC_Gutenberg_Emails_Loader {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init_post_type' ) );
		add_action( 'init', array( $this, 'create_emails' ) );
	}

	/**
	 * Init post types
	 */
	public function init_post_type() {
		if ( post_type_exists( 'woocommerce_email' ) ) {
			return;
		}

		register_post_type(
			'woocommerce_email',
			array(
				'labels'           => array(
					'name'          => __( 'WooCommerce Emails', 'woocommerce-gutenberg-emails' ),
					'singular_name' => __( 'WooCommerce Email', 'woocommerce-gutenberg-emails' ),
					'menu_name'     => __( 'Emails', 'woocommerce-gutenberg-emails' ),
				),
				'public'           => false,
				'menu_position'    => 56,
				'show_ui'          => true,
				'show_in_rest'     => true,
				'delete_with_user' => false,
				'map_meta_cap'     => true,
				'capability_type'  => 'post',
				'capabilities'     => array(
					// We don't want users to be able to create/delete WooCommerce emails,
					// so no user role has these capabilities by default.
					'delete_post'            => 'delete_woocommerce_emails',
					'delete_private_posts'   => 'delete_woocommerce_emails',
					'delete_published_posts' => 'delete_woocommerce_emails',
					'delete_others_posts'    => 'delete_woocommerce_emails',
					'create_posts'           => 'create_woocommerce_emails',
				),
			)
		);
	}

	/**
	 * Create emails content
	 */
	public function create_emails() {
		// @todo
	}
}

new WC_Gutenberg_Emails_Loader();
