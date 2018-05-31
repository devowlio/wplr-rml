import React from 'react';
import Button from 'wp/button';
import Notice from 'wp/notice';
require('./style.scss');

export default () => {
	return (
		<div className="wp-styleguide">
			<h1>WP React Component Library</h1>
			
			<Notice type="info">The text domain of the plugin is: "{window.wplr_rmlOpts.textDomain}" (localized variable)</Notice>
			<Notice type="info">The WP REST API URL of the plugin is: <a href={window.wplr_rmlOpts.restUrl + 'plugin'} target="_blank">{window.wplr_rmlOpts.restUrl}</a> (localized variable)</Notice>
			<Notice type="info">The is an informative notice</Notice>
			<Notice type="success">Your action was successful</Notice>
			<Notice type="error">An unexpected error has occurred</Notice>

			<div className="wp-styleguide--buttons">
				<h2>Buttons</h2>
				<Button type="primary">Primary Button</Button>
				<Button type="secondary">Secondary Button</Button>
			</div>
		</div>
	);
};
