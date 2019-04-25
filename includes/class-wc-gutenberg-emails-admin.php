<?php
/**
 * Admin changes for WooCommerce Gutenberg Emails.
 *
 * @package Woocommerce Gutenberg Emails
 */

/**
 * WC_Gutenberg_Emails_Admin Class.
 */
class WC_Gutenberg_Emails_Admin {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'manage_woocommerce_email_posts_columns', array( $this, 'add_post_list_columns' ) );
		add_filter( 'manage_woocommerce_email_posts_custom_column', array( $this, 'add_custom_column_data' ), 10, 2 );
	}

	/**
	 * Add columns to posts list.
	 *
	 * @param array $columns Array of columns.
	 * @return array
	 */
	public function add_post_list_columns( $columns ) {
		$columns['title'] = __( 'Subject', 'woocommerce-gutenberg-emails' );
		$columns['email'] = __( 'Email', 'woocommerce-gutenberg-emails' );

		// Move date column to end.
		$date_column = $columns['date'];
		unset( $columns['date'] );
		$columns['date'] = $date_column;

		return $columns;
	}

	/**
	 * Add column data for custom columns.
	 *
	 * @param string $column Column name.
	 * @param int    $post_id Post ID.
	 */
	public function add_custom_column_data( $column, $post_id ) {
		if ( 'email' === $column ) {
			$wc_emails = WC_Emails::instance();
			$emails    = $wc_emails->get_emails();
			$template  = get_post( $post_id );

			foreach ( $emails as $key => $email ) {
				if ( strtolower( $key ) === $template->post_name ) {
					echo esc_html( $emails[ $key ]->title );
					break;
				}
			}
		}
	}
}

new WC_Gutenberg_Emails_Admin();
