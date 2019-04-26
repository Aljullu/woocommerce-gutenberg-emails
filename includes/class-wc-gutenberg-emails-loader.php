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
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'create_templates' ) );
	}

	/**
	 * Init
	 */
	public function init() {
		$this->register_post_type();
		$this->register_blocks();
		add_filter( 'block_categories', array( $this, 'add_block_category' ) );

		$block_dependencies = array(
			'wp-blocks',
			'wp-element',
			'wp-i18n',
		);

		wp_register_script( 'wc-gutenberg-emails-order-details', plugins_url( 'build/order-details.js', WC_GUTENBERG_EMAILS_PLUGIN_FILE ), $block_dependencies, '0.1.0' );
		wp_register_style( 'wc-gutenberg-emails-order-details', plugins_url( 'build/order-details.css', WC_GUTENBERG_EMAILS_PLUGIN_FILE ), array(), '0.1.0' );
	}

	/**
	 * Register post type
	 */
	public function register_post_type() {
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
	 * Register blocks
	 */
	public function register_blocks() {
		register_block_type(
			'woocommerce-gutenberg-emails/order-details',
			array(
				'editor_script' => 'wc-gutenberg-emails-order-details',
				'style'         => 'wc-gutenberg-emails-order-details',
			)
		);
	}

	/**
	 * Create block category
	 *
	 * @param array $categories Array of categories.
	 * @return array Array of block categories.
	 */
	public function add_block_category( $categories ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'woocommerce-gutenberg-emails',
					'title' => __( 'WooCommerce Gutenberg Emails', 'woocommerce-gutenberg-emails' ),
					'icon'  => 'woocommerce',
				),
			)
		);
	}

	/**
	 * Create email template posts.
	 */
	public function create_templates() {
		global $wpdb, $pagenow;

		// Only run this on the WooCommerce Emails page.
		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification
		if ( 'edit.php' !== $pagenow || ! isset( $_GET['post_type'] ) || 'woocommerce_email' !== $_GET['post_type'] ) {
			return;
		}
		// phpcs:enable

		// Get already installed templates.
		$installed_templates = $wpdb->get_col(
			"SELECT post_name FROM {$wpdb->posts} WHERE post_type = 'woocommerce_email'"
		);

		$wc_emails = WC_Emails::instance();

		foreach ( $wc_emails->get_emails() as $key => $email ) {
			if ( in_array( strtolower( $key ), $installed_templates, true ) ) {
				continue;
			}

			wp_insert_post(
				array(
					'post_type'    => 'woocommerce_email',
					'post_name'    => $key,
					'post_title'   => $email->get_default_subject(),
					'post_content' => '', // @todo Add blocks content for email.
					'post_status'  => 'draft',
					'post_excerpt' => $email->description,
				)
			);
		}
	}
}

new WC_Gutenberg_Emails_Loader();
