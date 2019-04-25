<?php
/**
 * Initializes WooCommerce Gutenberg Emails email filters.
 *
 * @package Woocommerce Gutenberg Emails
 */

/**
 * WC_Gutenberg_Emails_Email Class.
 */
class WC_Gutenberg_Emails_Email {
	/**
	 * The single instance of the class
	 *
	 * @var WC_Gutenberg_Emails_Email
	 */
	protected static $_instance = null;

	/**
	 * WC_Email or child class.
	 *
	 * @var WC_Gutenberg_Emails_Email[]
	 */
	public $email_class = null;

	/**
	 * Main WC_Gutenberg_Emails_Email Instance.
	 *
	 * Ensures only one instance of WC_Gutenberg_Emails_Email is loaded or can be loaded.
	 *
	 * @since 2.1
	 * @static
	 * @return WC_Emails Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_mail_callback_params', array( $this, 'filter_email' ), 10, 2 );
	}

	/**
	 * Filter email subject and content for published templates.
	 *
	 * @param array  $mail_args Array of mail arguments.
	 * @param object $wc_email WC_Email or child class.
	 * @return array
	 */
	public function filter_email( $mail_args, $wc_email ) {
		$this->email_class = $wc_email;
		$template          = $this->get_template();

		if ( ! $template ) {
			return $mail_args;
		}

		// Email subject.
		$mail_args[1] = $template->post_title;
		// Email content.
		$mail_args[2] = $this->get_content();

		return $mail_args;
	}

	/**
	 * Get the post template for an email.
	 *
	 * @return WP_Post|bool
	 */
	public function get_template() {
		$templates = get_posts(
			array(
				'post_type'      => 'woocommerce_email',
				'posts_per_page' => 1,
				'name'           => strtolower( get_class( $this->email_class ) ),
				'post_status'    => 'publish',
			)
		);

		if ( count( $templates ) ) {
			return $templates[0];
		}

		return false;
	}

	/**
	 * Replace the email content with the saved template.
	 *
	 * @return string
	 */
	public function get_content() {
		$email_class = get_class( $this->email_class );
		$template    = $this->get_template();

		if ( 'plain' === $this->email_class->get_email_type() ) {
			$email_content = wordwrap( preg_replace( $this->email_class->plain_search, $this->email_class->plain_replace, wp_strip_all_tags( $template->post_content ) ), 70 );
		} else {
			$wc_email      = new WC_Email();
			$email_content = $wc_email->style_inline( $template->post_content );
		}

		return $email_content;
	}
}
WC_Gutenberg_Emails_Email::instance();
