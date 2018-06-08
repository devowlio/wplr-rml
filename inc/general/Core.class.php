<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\general;
use MatthiasWeb\RealMediaLibrary\WPLR\base;
use MatthiasWeb\RealMediaLibrary\WPLR\rest;
use MatthiasWeb\RealMediaLibrary\WPLR\sync;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

// Include files, where autoloading is not possible, yet
require_once(WPLR_RML_INC . 'base/Core.class.php');

/**
 * Singleton core class which handles the main system for plugin. It includes
 * registering of the autoloader, all hooks (actions & filters) (see base\Core class).
 */
class Core extends base\Core {
    
    /**
     * Singleton instance.
     */
    private static $me;
    
    /**
     * The main service.
     * 
     * @see rest\Service
     */
    private $service;
    
    /**
     * Application core constructor.
     */
    protected function __construct() {
        parent::__construct();
        
        // Register all your before init hooks here.
        // Note: At this point isn't sure if RML is installed and the min version is reached.
        // It is not recommenend to use base\Base::rmlVersionReached() here, you should use it in
        // all your hooks implementations.
        
        // Register all your before init hooks here
        add_action('plugins_loaded', array($this, 'updateDbCheck'));
        add_action('RML/Activate', array($this, 'rml_activate'));
        add_action('RML/Migration', array($this, 'rml_activate'), 10, 0);
        add_action('RML/Creatable/Register', array($this, 'creatables'));
    }
    
    public function creatables() {
        wp_rml_register_creatable(WPLR_RML_NS . '\\folder\\Root', WPLR_RML_TYPE_ROOT);
        wp_rml_register_creatable(WPLR_RML_NS . '\\folder\\Folder', WPLR_RML_TYPE_FOLDER);
        wp_rml_register_creatable(WPLR_RML_NS . '\\folder\\Collection', WPLR_RML_TYPE_COLLECTION);
    }
    
    /**
     * The init function is fired even the init hook of WordPress. If possible
     * it should register all hooks to have them in one place.
     */
    public function init() {
        // Check if min Real Media Library version is reached...
        if (!$this->rmlVersionReached()) {
            // WP Real Media Library version not reached
            require_once(WPLR_RML_INC . 'others/fallback-rml.php');
            return;
        }
        
        // Check if WPLR is installed
        if (!$this->wplrInstalled()) {
            require_once(WPLR_RML_INC . 'others/fallback-wplr.php');
            return;
        }
        
        $this->service = new rest\Service();
        $folders = new sync\Folders();
        $attachments = new sync\Attachments();
        
        // Register all your hooks here
        add_action('rest_api_init', array($this->getService(), 'rest_api_init'));
        add_action('RML/Scripts', array($this->getAssets(), 'admin_enqueue_scripts'));
        add_action('wplr_reset', array($folders, 'reset'), 10, 0);
        add_action('wplr_create_folder', array($folders, 'create_folder'), 10, 3);
        add_action('wplr_create_collection', array($folders, 'create_collection'), 10, 3);
        add_action('wplr_remove_folder', array($folders, 'remove_folder'), 10, 1);
        add_action('wplr_remove_collection', array($folders, 'remove_collection'), 10, 1);
        add_action('wplr_update_folder', array($folders, 'update_folder'), 10, 2);
        add_action('wplr_update_collection', array($folders, 'update_collection'), 10, 2);
        add_action('wplr_move_folder', array($folders, 'move_folder'), 10, 3);
        add_action('wplr_move_collection', array($folders, 'move_collection'), 10, 3);

        add_action('RML/Die', array($attachments, 'rml_die'), 10, 0);
        add_action('wplr_add_media_to_collection', array($attachments, 'add_to_collection'), 10, 2);
        add_action('wplr_remove_media_from_collection', array($attachments, 'remove_from_collection'), 10, 2);
    }
	
	public function rml_activate() {
	    $this->getActivator()->install();
	}
	
    /**
     * Get the service.
     * 
     * @returns rest\Service
     */
    public function getService() {
        return $this->service;
    }
    
    /**
     * Get singleton core class.
     * 
     * @returns Core
     */
    public static function getInstance() {
        return !isset(self::$me) ? self::$me = new Core() : self::$me;
    }
}