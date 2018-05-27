<?php
namespace MatthiasWeb\WPRJSS\general;
use MatthiasWeb\WPRJSS\base;
use MatthiasWeb\WPRJSS\menu;
use MatthiasWeb\WPRJSS\rest;
use MatthiasWeb\WPRJSS\widget;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

// Include files, where autoloading is not possible, yet
require_once(WPRJSS_INC . 'base/Core.class.php');

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
        add_action('widgets_init', array($this, 'widgets_init'));
    }
    
    /**
     * The init function is fired even the init hook of WordPress. If possible
     * it should register all hooks to have them in one place.
     */
    public function init() {
        // Check if min Real Media Library version is reached...
        if (!$this->rmlVersionReached()) {
            // WP Real Media Library version not reached
            require_once(WPRJSS_INC . 'others/fallback-rml.php');
            return;
        }
        
        $this->service = new rest\Service();
        
        // Register all your hooks here
        add_action('rest_api_init', array($this->getService(), 'rest_api_init'));
        add_action('admin_enqueue_scripts', array($this->getAssets(), 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this->getAssets(), 'wp_enqueue_scripts'));
        add_action('admin_menu', array(new menu\Page(), 'admin_menu'));
    }
    
    /**
	 * Register widgets.
	 */
	public function widgets_init() {
	    if ($this->rmlVersionReached()) {
	        register_widget(WPRJSS_NS . '\\widget\\Widget');
	    }
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