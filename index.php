<?php
/**
 * @wordpress-plugin
 * Plugin Name: 	WP/LR Sync Folders
 * Plugin URI:		https://wordpress.org/plugins/wplr-rml
 * Description: 	Displays the hierarchy of folders and collections nicely on the left side of your Media Library
 * Author:          Matthias Günter <support@matthias-web.com>
 * Author URI:		https://matthias-web.com
 * Version: 		0.1.0
 * Text Domain:		wplr-rml
 * Domain Path:		/languages
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * Plugin constants. This file is procedural coding style for initialization of
 * the plugin core and definition of plugin configuration.
 */
if (defined('WPLR_RML_PATH')) return;
define('WPLR_RML_FILE', __FILE__);
define('WPLR_RML_PATH', dirname(WPLR_RML_FILE));
define('WPLR_RML_INC',	trailingslashit(path_join(WPLR_RML_PATH, 'inc')));
define('WPLR_RML_MIN_PHP', '5.3.0'); // Minimum of PHP 5.3 required for autoloading and namespacing
define('WPLR_RML_MIN_WP', '4.4.0'); // Minimum of WordPress 4.4 required
define('WPLR_RML_MIN_RML', '4.0.0'); // Minimum version of Real Media Library
define('WPLR_RML_NS', 'MatthiasWeb\\RealMediaLibrary\WPLR');
define('WPLR_RML_DB_PREFIX', 'wplr_rml'); // The table name prefix wp_{prefix}
define('WPLR_RML_OPT_PREFIX', 'wplr_rml'); // The option name prefix in wp_options
//define('WPLR_RML_TD', ''); This constant is defined in the core class. Use this constant in all your __() methods
//define('WPLR_RML_VERSION', ''); This constant is defined in the core class
//define('WPLR_RML_DEBUG', true); This constant should be defined in wp-config.php to enable the Base::debug() method

// Check PHP Version and print notice if minimum not reached, otherwise start the plugin core
require_once(WPLR_RML_INC . "others/" . (version_compare(phpversion(), WPLR_RML_MIN_PHP, ">=") ? "start.php" : "phpfallback.php"));