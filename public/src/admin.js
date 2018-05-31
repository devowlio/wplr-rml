import ReactDOM from 'react-dom';
import React from 'react';
import ComponentLibrary from 'component-library';

let widget = document.getElementById( 'wplr_rml-wp-react-component-library' );

if (widget) {
	ReactDOM.render(
		<ComponentLibrary />,
		widget
	);
}