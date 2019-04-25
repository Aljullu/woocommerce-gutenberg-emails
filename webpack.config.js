/**
 * External dependencies
 */
const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CleanWebpackPlugin = require( 'clean-webpack-plugin' );
const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		'order-details': './src/blocks/order-details/index.js',
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.s[c|a]ss$/,
				use: [
					'style-loader',
					MiniCssExtractPlugin.loader,
					{ loader: 'css-loader', options: { importLoaders: 1 } },
				],
			},
		],
	},
	plugins: [
		new CleanWebpackPlugin(),
		new MiniCssExtractPlugin( {
			filename: '[name].css',
		} ),
	],
};
