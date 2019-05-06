/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Placeholder blocks.
 */
const placeholders = [
	{
		name: 'site-title',
		title: __( 'Site Title', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the site title.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'order-date',
		title: __( 'Order Date', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the date the order was placed.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'order-number',
		title: __( 'Order Number', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the order number.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'order-billing-full-name',
		title: __( 'Billing Full Name', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the full billing name from the order.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'user-pass',
		title: __( 'User Password', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the user password.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'user-login',
		title: __( 'User Login', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the user login name.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'lost-password-url',
		title: __( 'Lost Password URL', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the URL to the lost password page.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'user-email',
		title: __( 'User Email', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the user email address.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'password-generated',
		title: __( 'Generated Password', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the randomly generated password for the user.', 'woocommerce-gutenberg-emails' ),
	},
	{
		name: 'customer-note',
		title: __( 'Customer Note', 'woocommerce-gutenberg-emails' ),
		description: __( 'Display the added customer note.', 'woocommerce-gutenberg-emails' ),
	},
];

/**
 * Register each of the placeholder blocks.
 */
placeholders.forEach( ( placeholder ) => {
	registerBlockType( 'woocommerce-gutenberg-emails/' + placeholder.name, {
		title: placeholder.title,
		category: 'woocommerce-gutenberg-emails',
		keywords: [ __( 'WooCommerce', 'woocommerce-gutenberg-emails' ) ],
		description: placeholder.description,

		/**
		 * Renders and manages the block.
		 *
		 * @return {Object} Editor component.
		 */
		edit() {
			return (
				<span className="placeholder">
					{ '{' + placeholder.name.replace( '-', '_' ) + '}' }
				</span>
			);
		},

		/**
		 * Save the block content in the post content.
		 *
		 * @return {Object} Visible component.
		 */
		save() {
			return (
				<span className="placeholder">
					{ '{' + placeholder.name.replace( '-', '_' ) + '}' }
				</span>
			);
		},
	} );
} );
