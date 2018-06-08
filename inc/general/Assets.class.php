<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\general;
use MatthiasWeb\RealMediaLibrary\WPLR\base;
use MatthiasWeb\RealMediaLibrary\WPLR\rest;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * Asset management for frontend scripts and styles.
 */
class Assets extends base\Assets {
    
    /**
     * Enqueue scripts and styles depending on the type. This function is called
     * from both admin_enqueue_scripts and wp_enqueue_scripts. You can check the
     * type through the $type parameter. In this function you can include your
     * external libraries from public/lib, too.
     * 
     * @param string $type The type (see base\Assets constants)
     */
    public function enqueue_scripts_and_styles($type) {
        $this->enqueueScript('wplr-rml', 'admin.js', array('react-dom'));
	    $this->enqueueStyle('wplr-rml', 'admin.css');
	    wp_localize_script('wplr-rml', 'wplr_rmlOpts', $this->adminLocalizeScript());
    }
    
    /**
     * Localize the WordPress admin backend.
     * 
     * @returns array
     */
    public function adminLocalizeScript() {
        return array(
            'textDomain' => WPLR_RML_TD,
            'restUrl' => rest\Service::getUrl(rest\Service::SERVICE_NAMESPACE)
        );
    }
    
    /**
     * Enqueue scripts and styles for admin pages.
     */
    public function admin_enqueue_scripts() {
        $this->enqueue_scripts_and_styles(base\Assets::TYPE_ADMIN);
    }
    
    /**
     * Enqueue scripts and styles for frontend pages.
     */
    public function wp_enqueue_scripts() {
        $this->enqueue_scripts_and_styles(base\Assets::TYPE_FRONTEND);
    }
}