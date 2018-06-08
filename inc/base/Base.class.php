<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * Base class for all available classes for the plugin.
 */
abstract class Base {
    /**
	 * Checks if the minimum version of Real Media Library is reached.
	 * 
	 * @returns boolean
	 */
	public function rmlVersionReached() {
	    return defined('RML_VERSION') && version_compare(RML_VERSION, WPLR_RML_MIN_RML, '>=');
	}
	
	/**
	 * Check if WPLR plugin is installed.
	 * 
	 * @returns boolean
	 */
	public function wplrInstalled() {
	    return class_exists('Meow_WPLR_Sync_Core');
	}
    
    /**
     * Simple-to-use error_log debug log. This debug is only outprintted when
     * you define WPLR_RML_DEBUG=true constant in wp-config.php 
     * 
     * @param mixed $message The message
     * @param string $methodOrFunction __METHOD__ OR __FUNCTION__
     */
    public function debug($message, $methodOrFunction = null) {
        if (defined('WPLR_RML_DEBUG') && WPLR_RML_DEBUG) {
            $log = (empty($methodOrFunction) ? "" : "(" . $methodOrFunction . ")") . ": " . (is_string($message) ? $message : json_encode($message));
            error_log("WPLR_RML_DEBUG " . $log);
        }
    }
    
    /**
     * Get a plugin relevant table name depending on the WPLR_RML_DB_PREFIX constant.
     * 
     * @param string $name Append this name to the plugins relevant table with _{$name}.
     * @param boolean $isRml If true the realmedialibrary prefix is used
     * @returns string
     */
    public function getTableName($name = "", $isRml = false) {
        global $wpdb;
        return $wpdb->prefix . ($isRml === true ? 'realmedialibrary' : ($isRml === 'wplr' ? 'lrsync' : WPLR_RML_DB_PREFIX )) . (($name == "") ? "" : "_" . $name);
    }
}