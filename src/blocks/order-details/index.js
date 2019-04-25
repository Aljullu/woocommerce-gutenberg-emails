/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * Register and run the "Order Details" block.
 */
registerBlockType( 'woocommerce-gutenberg-emails/order-details', {
	title: __( 'Order Details', 'woocommerce-gutenberg-emails' ),
	category: 'woocommerce-gutenberg-emails',
	keywords: [ __( 'WooCommerce', 'woocommerce-gutenberg-emails' ) ],
	description: __(
		'Display order details in a table.',
		'woocommerce-gutenberg-emails'
	),

	/**
	 * Renders and manages the block.
	 *
	 * @return {Object} Editor component.
	 */
	edit() {
		return (
			<span className="lorem-ipsum">
				{ 'Lorem ipsum' }
			</span>
		);
	},

	/**
	 * Block content is rendered in PHP, not via save function.
	 *
	 * @return {Object} Visible component.
	 */
	save() {
		return (
			<span className="lorem-ipsum">
				{ 'Lorem ipsum' }
			</span>
		);
	},
} );
