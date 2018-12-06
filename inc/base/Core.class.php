<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\base;
use MatthiasWeb\RealMediaLibrary\WPLR\general;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

// Include files, where autoloading is not possible, yet
require_once(WPLR_RML_INC . 'base/Base.class.php');

/**
 * Base class for the applications Core class.
 */
abstract class Core extends Base {
    /**
     * The relative path to the composer autoload file.
     */
    const COMPOSER_AUTOLOAD = 'vendor/autoload.php';
    
    /**
     * The stored plugin data.
     * 
     * @see getPluginData()
     */
    private $plugin_data;
    
    /**
     * The plugins activator class.
     * 
     * @see Activator
     */
    private $activator;
    
    /**
     * The plugins asset class.
     * 
     * @see Assets
     */
    private $assets;
    
    /**
     * The constructor handles the core startup mechanism.
     * 
     * The constructor is protected because a factory method should only create
     * a Core object.
     */
    protected function __construct() {
        // Define lazy constants
        define('WPLR_RML_TD', $this->getPluginData("TextDomain"));
        define('WPLR_RML_VERSION', $this->getPluginData("Version"));

        // Register autoload
        spl_autoload_register(array($this, 'autoloadRegister'));
        
        // Register composer autoload
        $composer_path = path_join(WPLR_RML_PATH, Core::COMPOSER_AUTOLOAD);
        if (file_exists($composer_path)) {
            include_once($composer_path);
        }
        
        // Register immediate actions and filters
        $this->activator = new general\Activator();
        $this->assets = new general\Assets();
        
        add_action('plugins_loaded', array($this, 'i18n'));
        add_action('init', array($this, 'init'));
        register_activation_hook(WPLR_RML_FILE, array($this->getActivator(), 'activate'));
        register_deactivation_hook(WPLR_RML_FILE, array($this->getActivator(), 'deactivate'));
    }
    
    /**
     * Autoload PHP files (classes and interfaces). It respects the given namespace
     * within the inc/ folder. Each subnamespace must be available as own folder.
     * Class files should be defined as ClassName.class.php. Interface files should
     * be defined as IName.interface.php.
     * 
     * @param string $className Full qualified class name
     */
    public function autoloadRegister($className) {
        $namespace = WPLR_RML_NS . "\\";
        if (0 === strpos($className, $namespace)) {
            $name = substr($className, strlen($namespace));
            $last = explode("\\", $name);
            $prefix = WPLR_RML_INC . str_replace('\\', '/', $name) . '.';
            $filesToCheck = array(
                $prefix . 'class.php',
                $prefix . 'interface.php',
                $prefix . 'trait.php'
            );
            
            foreach ($filesToCheck as $filename) {
                if (file_exists($filename)) {
                    require_once($filename);
                }
            }
        }
    }
    
    /**
     * The plugin is loaded. Start to register the localization (i18n) files.
     */
    public function i18n() {
        load_plugin_textdomain(WPLR_RML_TD, false, dirname(plugin_basename(WPLR_RML_FILE)) . $this->getPluginData("DomainPath"));
    }
    
    /**
     * Updates the database version in the options table.
     * It also installs the needed database tables.
     */
    public function updateDbCheck() {
        $installed = get_option( WPLR_RML_OPT_PREFIX . '_db_version' );
        if ($installed != WPLR_RML_VERSION) {
            $this->debug("(Re)install the database tables", __FUNCTION__);
            $this->getActivator()->install();
            
            // Check if migratin notice is needed (https://git.io/fpDZi)
            if (version_compare($installed, '1.1.0', '<')) {
                update_option(WPLR_RML_OPT_PREFIX . general\Core::OPT_NAME_MIGRATION_ISSUE_3, '1');
            }
        }
    }
    
    /**
     * Gets the plugin data.
     * 
     * @param string $key The key of the data to return
     * @returns string[]|string
     * @see https://developer.wordpress.org/reference/functions/get_plugin_data/
     */
    public function getPluginData($key = null) {
        require_once(ABSPATH . '/wp-admin/includes/plugin.php');
        $data = isset($this->plugin_data) ? $this->plugin_data : $this->plugin_data = get_plugin_data(WPLR_RML_FILE, true, false);
        return $key === null ? $data : $data[$key];
    }
    
    /**
     * @returns Activator
     */
    public function getActivator() {
        return $this->activator;
    }
    
    /**
     * @returns Assets
     */
    public function getAssets() {
        return $this->assets;
    }
}