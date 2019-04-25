/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/editor';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Block from './block';
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
		'woo-gutenberg-products-block'
	),

	/**
	 * Renders and manages the block.
	 */
	edit( props ) {
		return <Block { ...props } />;
	},

	/**
	 * Block content is rendered in PHP, not via save function.
	 */
	save() {
		return <InnerBlocks.Content />;
	},
} );
