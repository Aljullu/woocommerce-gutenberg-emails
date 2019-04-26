/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, RichText } from '@wordpress/editor';

const renderTemplate = ( { image, header, content, footer } ) => {
	const isRTL = () => document.documentElement.dir === 'rtl';
	return (
		<div id="wrapper" dir={ isRTL() ? 'rtl' : 'ltr' }>
			<table border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<div id="template_header_image">
							{ image }
						</div>
						<table border="0" cellPadding="0" cellSpacing="0" width="600" id="template_container">
							<tr>
								<td align="center" valign="top">
									<table border="0" cellPadding="0" cellSpacing="0" width="600" id="template_header">
										<tr>
											<td id="header_wrapper">
												{ header }
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<table border="0" cellPadding="0" cellSpacing="0" width="600" id="template_body">
										<tr>
											<td valign="top" id="body_content">
												<table border="0" cellPadding="20" cellSpacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
																{ content }
															</div>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<table border="0" cellPadding="10" cellSpacing="0" width="600" id="template_footer">
										<tr>
											<td valign="top">
												<table border="0" cellPadding="10" cellSpacing="0" width="100%">
													<tr>
														<td colSpan="2" valign="middle" id="credit">
															{ footer }
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	);
};

/**
 * Register and run the "Order Details" block.
 */
registerBlockType( 'woocommerce-gutenberg-emails/email-wrapper', {
	title: __( 'Email Wrapper', 'woocommerce-gutenberg-emails' ),
	category: 'woocommerce-gutenberg-emails',
	keywords: [ __( 'WooCommerce', 'woocommerce-gutenberg-emails' ) ],
	description: __(
		'Adds the header/footer for emails.',
		'woocommerce-gutenberg-emails'
	),
	supports: {
		multiple: false,
	},
	attributes: {
		footer: {
			type: 'string',
			source: 'html',
			default: '{footer}',
			selector: '#credit',
		},
		headerImage: {
			type: 'string',
			source: 'html',
			default: '{header_image}',
			selector: '#template_header_image',
		},
		heading: {
			type: 'string',
			source: 'html',
			default: '{heading}',
			selector: 'h1',
		},
	},

	/**
	 * Renders and manages the block.
	 *
	 * @return {Object} Editor component.
	 */
	edit( { attributes, setAttributes } ) {
		const { footer, headerImage, heading } = attributes;
		return renderTemplate( {
			image: (
				<RichText
					identifier="header_image"
					value={ headerImage }
					onChange={
						( nextValue ) => setAttributes( {
							headerImage: nextValue,
						} )
					}
					placeholder={ __( 'Add your header image…', 'woocommerce-gutenberg-emails' ) }
				/>
			),
			header: (
				<RichText
					tagName="h1"
					identifier="heading"
					value={ heading }
					onChange={
						( nextValue ) => setAttributes( {
							heading: nextValue,
						} )
					}
					placeholder={ __( 'Write a heading…', 'woocommerce-gutenberg-emails' ) }
				/>
			),
			content: <InnerBlocks />,
			footer: (
				<RichText
					identifier="footer"
					value={ footer }
					onChange={
						( nextValue ) => setAttributes( {
							footer: nextValue,
						} )
					}
					placeholder={ __( 'Write a footer…', 'woocommerce-gutenberg-emails' ) }
				/>
			),
		} );
	},

	/**
	 * Block content is rendered in PHP, not via save function.
	 *
	 * @return {Object} Visible component.
	 */
	save( { attributes } ) {
		const { footer, headerImage, heading } = attributes;
		return renderTemplate( {
			image: <RichText.Content value={ headerImage } />,
			header: <RichText.Content tagName="h1" value={ heading } />,
			content: <InnerBlocks.Content />,
			footer: <RichText.Content value={ footer } />,
		} );
	},
} );
