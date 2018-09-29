var path = require( 'path' ),
	webpack = require( 'webpack' ),
	exec = require('child_process').exec,
	NODE_ENV = process.env.NODE_ENV || 'development',
	MiniCssExtractPlugin = require( 'mini-css-extract-plugin' ),
	dist = path.join( __dirname, 'public', NODE_ENV === 'production' ? 'dist' : 'dev' );

module.exports = {
	mode: NODE_ENV,
	entry: {
		admin: './public/src/admin.js'
	},
	output: {
		path: dist,
		filename: "[name].js"
	},
	externals: {
		'react': 'React',
		'react-dom': 'ReactDOM',
		'jquery': 'jQuery',
		'immer': 'immer',
		'i18n-react': 'window[\'i18n-react\']',
		'react-aiot': 'ReactAIOT',
		'mobx': 'mobx',
		'mobx-state-tree': 'mobxStateTree',
		'rml': 'rml',
		'rmlopts': 'rmlOpts',
		'wp': 'wp',
		'_': '_',
		'wpApiSettings': 'wpApiSettings'
	},
	devtool: '#source-map',
	module: {
	    rules: [{
	    	test: /\.js$/,
			exclude: /(disposables)/,
			use: 'babel-loader?cacheDirectory'
	    }, {
	    	test: /\.scss$/,
	        use: [MiniCssExtractPlugin.loader, 'css-loader', {
				loader: 'postcss-loader',
				options: {
					config: {
						ctx: {
							clean: {}
						}
					}
				}
			}, 'sass-loader']
	    }]
	},
	resolve: {
		extensions: [ '.js', '.jsx' ],
		modules: [ 'node_modules', 'public/src' ]
	},
	plugins: [
		new webpack.DefinePlugin({
			// NODE_ENV is used inside React to enable/disable features that should only be used in development
			'process.env': {
				NODE_ENV: JSON.stringify( NODE_ENV ),
				env: JSON.stringify( NODE_ENV )
			}
		}),
		new MiniCssExtractPlugin( '[name].css' ),
		new ((function() {
			// Short plugin to run script on build finished to recreate the cachebuster
			function WebPackRecreateCachebuster() { }
			WebPackRecreateCachebuster.prototype.apply = function(compiler) {
				compiler.plugin('done', function(compilation, callback) {
					setTimeout(function() { console.log('Running webpack-build-done script...'); }, 0);
					exec('npm run webpack-build-done', function(error, stdout, stderr) { console.log(stdout); });
				});
			};
			return WebPackRecreateCachebuster;
		})())()
	]
};