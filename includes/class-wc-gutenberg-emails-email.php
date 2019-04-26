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
		$mail_args[1] = $this->replace_placeholders( $template->post_title );
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
	 * Get an array of strings to find for replacement.
	 *
	 * @return array
	 */
	public function get_plain_search() {
		$plain_search = array(
			'/^\s+/m', // Multiple blank lines.
		);

		return array_merge( $this->email_class->plain_search, $plain_search );
	}

	/**
	 * Get an array of items used to replace found strings.
	 *
	 * @return array
	 */
	public function get_plain_replace() {
		$plain_replace = array(
			"\r\n", // Multiple blank lines.
		);

		return array_merge( $this->email_class->plain_replace, $plain_replace );
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
			$email_content = wordwrap( preg_replace( $this->get_plain_search(), $this->get_plain_replace(), wp_strip_all_tags( $template->post_content ) ), 70 );
		} else {
			$wc_email      = new WC_Email();
			$email_content = $wc_email->style_inline( $template->post_content );
		}

		return $this->replace_placeholders( $email_content );
	}

	/**
	 * Get placeholders from email class objects.
	 *
	 * @return array
	 */
	public function get_placeholders() {
		$placeholders = array();

		$placeholders['{site_title}']   = $this->email_class->get_blogname();
		$placeholders['{blogname}']     = $this->email_class->get_blogname();
		$placeholders['{heading}']      = $this->email_class->get_default_heading();
		$placeholders['{footer}']       = wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) );
		$placeholders['{header_image}'] = '';
		if ( $img = get_option( 'woocommerce_email_header_image' ) ) {
			$placeholders['{header_image}'] = '<p style="margin-top:0;"><img src="' . esc_url( $img ) . '" alt="' . get_bloginfo( 'name', 'display' ) . '" /></p>';
		}

		if ( is_a( $this->email_class->object, 'WC_Order' ) ) {
			$placeholders['{order_date}']              = wc_format_datetime( $this->email_class->object->get_date_created() );
			$placeholders['{order_number}']            = $this->email_class->object->get_order_number();
			$placeholders['{order_billing_full_name}'] = $this->email_class->object->get_formatted_billing_full_name();
		}

		if ( is_a( $this->email_class->object, 'WP_User' ) ) {
			$placeholders['{user_login}']        = stripslashes( $this->email_class->object->user_login );
			$placeholders['{user_email}']        = stripslashes( $this->email_class->object->user_email );
			$placeholders['{lost_password_url}'] = esc_url(
				add_query_arg(
					array(
						'key' => $this->email_class->reset_key,
						'id'  => $this->email_class->object->ID,
					),
					wc_get_endpoint_url(
						'lost-password',
						'',
						wc_get_page_permalink( 'myaccount' )
					)
				)
			);
		}

		if ( property_exists( $this->email_class, 'user_pass' ) ) {
			$placeholders['{user_pass}'] = $this->email_class->user_pass;
		}
		if ( property_exists( $this->email_class, 'password_generated' ) ) {
			$placeholders['{password_generated}'] = $this->email_class->password_generated;
		}
		if ( property_exists( $this->email_class, 'customer_note' ) ) {
			$placeholders['{customer_note}'] = $this->email_class->customer_note;
		}

		return $placeholders;
	}

	/**
	 * Replace placeholders with object content.
	 *
	 * @param string $string String to search.
	 * @return string
	 */
	public function replace_placeholders( $string ) {
		$placeholders = $this->get_placeholders();
		$find         = array_keys( $placeholders );
		$replace      = array_values( $placeholders );

		return apply_filters( 'woocommerce_email_format_string', str_replace( $find, $replace, $string ), $this );
	}
}
WC_Gutenberg_Emails_Email::instance();
