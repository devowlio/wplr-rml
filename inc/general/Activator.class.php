<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\general;
use MatthiasWeb\RealMediaLibrary\WPLR\base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * The activator class handles the plugin relevant activation hooks: Uninstall, activation,
 * deactivation and installation. The "installation" means installing needed database tables.
 */
class Activator extends base\Base {
    /**
     * Method gets fired when the user activates the plugin.
     */
    public function activate() {
        // Your implementation...
    }
    
    /**
     * Method gets fired when the user deactivates the plugin.
     */
    public function deactivate() {
        // Your implementation...
    }
    
    /**
     * Install tables, stored procedures or whatever in the database.
     * 
     * @param boolean $errorlevel Set true to throw errors
     * @param callable $installThisCallable Set a callable to install this one instead of the default
     */
    public function install($errorlevel = false, $installThisCallable = null) {
    	global $wpdb;
    
    	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    	$charset_collate = $wpdb->get_charset_collate();

    	// Avoid errors printed out
    	if ($errorlevel === false) {
    		$show_errors = $wpdb->show_errors(false);
    		$suppress_errors = $wpdb->suppress_errors(false);
    		$errorLevel = error_reporting();
    		error_reporting(0);
    	}
    	
    	// Table wp_wprjss
    	if ($installThisCallable === null) {
    	    // Your table installation here...
    	    if ($this->rmlVersionReached()) {
    	        // Add 'wplr_id' to realmedialibrary table
    	        $table_name = $this->getTableName("", true);
    	        $row = $wpdb->get_results("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table_name' AND column_name = 'wplr_id';");
            	if (empty($row)) {
            		$wpdb->query("ALTER TABLE $table_name ADD wplr_id INT(11) DEFAULT NULL");
            	}
    	    }
    	}else{
    		call_user_func($installThisCallable);
    	}
    	
    	if ($errorlevel === false) {
    		$wpdb->show_errors($show_errors);
    		$wpdb->suppress_errors($suppress_errors);
    		error_reporting($errorLevel);
    	}
    	
    	if ($installThisCallable === null && $this->rmlVersionReached()) {
    		update_option( WPLR_RML_OPT_PREFIX . '_db_version', WPLR_RML_VERSION );
    	}
    }
}