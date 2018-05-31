<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

if (!function_exists('wplr_rml_skip_php_admin_notice')) {
	/**
	 * Show an admin notice to administrators when the minimum PHP version
	 * could not be reached. The error message is only in english available.
	 */
    function wplr_rml_skip_php_admin_notice() {
        if (current_user_can('install_plugins')) {
        	extract(get_plugin_data(WPLR_RML_FILE, true, false));
        	echo '<div class=\'notice notice-error\'>
				<p><strong>' . $Name . '</strong> could not be initialized because you need minimum PHP version ' . WPLR_RML_MIN_PHP . ' ... you are running: ' . phpversion() . '.
			</div>';
        }
    }
}
add_action('admin_notices', 'wplr_rml_skip_php_admin_notice');