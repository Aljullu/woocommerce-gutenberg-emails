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
		'woocommerce-gutenberg-emails'
	),

	/**
	 * Renders and manages the block.
	 *
	 * @param {Object} props
	 * @return {Object} Editor component.
	 */
	edit( props ) {
		return <Block { ...props } />;
	},

	/**
	 * Block content is rendered in PHP, not via save function.
	 *
	 * @return {Object} Visible component.
	 */
	save() {
		return <InnerBlocks.Content />;
	},
} );
