<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

if (!function_exists('wplr_rml_skip_wplr_admin_notice')) {
	/**
	 * Show an admin notice to administrators when the minimum RML version
	 * could not be reached. The error message is only in english available.
	 */
    function wplr_rml_skip_wplr_admin_notice() {
        if (current_user_can('install_plugins')) {
        	extract(get_plugin_data(WPLR_RML_FILE, true, false));
        	global $wp_version;
        	echo '<div class=\'notice notice-error\'>
			    <p>The plugin <strong>' . $Name . '</strong> (Add-On) could not be initialized because <a href="https://wordpress.org/plugins/wplr-sync/" target="_blank"><b>WP/LR Sync</b></a> is not active (maybe not installed neither).</p>
			</div>';
        }
    }
}
add_action('admin_notices', 'wplr_rml_skip_wplr_admin_notice');