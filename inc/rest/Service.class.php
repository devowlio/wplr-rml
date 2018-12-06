<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\rest;
use MatthiasWeb\RealMediaLibrary\WPLR\base;
use MatthiasWeb\RealMediaLibrary\WPLR\general;
use MatthiasWeb\RealMediaLibrary\rest as rmlRest;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * Create a REST Service.
 */
class Service extends base\Base {
    
    /**
     * The namespace for this service.
     * 
     * @see Service::getUrl()
     */
    const SERVICE_NAMESPACE = 'wplr-rml/v1';
    
    /**
     * Register endpoints.
     */
    public function rest_api_init() {
        register_rest_route(Service::SERVICE_NAMESPACE, '/plugin', array(
            'methods' => 'GET',
            'callback' => array($this, 'routePlugin')
        ));
        
        register_rest_route(Service::SERVICE_NAMESPACE, '/reset/shortcuts', array(
            'methods' => 'DELETE',
            'callback' => array($this, 'routeDeleteShortcuts')
        ));
    }
    
    /**
     * @api {get} /wprjss/v1/plugin Get plugin information
     * @apiHeader {string} X-WP-Nonce
     * @apiName GetPlugin
     * @apiGroup Plugin
     *
     * @apiSuccessExample {json} Success-Response:
     * {
     *     WC requires at least: "",
     *     WC tested up to: "",
     *     Name: "WP ReactJS Starter",
     *     PluginURI: "https://matthias-web.com/wordpress",
     *     Version: "0.1.0",
     *     Description: "This WordPress plugin demonstrates how to setup a plugin that uses React and ES6 in a WordPress plugin. <cite>By <a href="https://matthias-web.com">Matthias Guenter</a>.</cite>",
     *     Author: "<a href="https://matthias-web.com">Matthias Guenter</a>",
     *     AuthorURI: "https://matthias-web.com",
     *     TextDomain: "wp-reactjs-starter",
     *     DomainPath: "/languages",
     *     Network: false,
     *     Title: "<a href="https://matthias-web.com/wordpress">WP ReactJS Starter</a>",
     *     AuthorName: "Matthias Guenter"
     * }
     * @apiVersion 0.1.0
     */
    public function routePlugin() {
        return new \WP_REST_Response(general\Core::getInstance()->getPluginData());
    }
    
    /**
     * @api {delete} /wplr-rml/v1/reset/shortcuts Delete the shortcuts which are associated with WP/LR attachments
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {int} post_name The new length of the post_name field
     * @apiParam {int} guid The new length of the guid field
     * @apiName DeleteShortcuts
     * @apiGroup Plugin
     * @apiVersion 0.1.0
     * @apiPermission manage_options
     */
    public function routeDeleteShortcuts($request) {
        global $wpdb;
        if (($permit = rmlRest\Service::permit()) !== null) return $permit;
        
        $table_name_posts = $this->getTableName('posts', true);
        $table_name_lrsync = $this->getTableName('', 'wplr');
        $sql = 'SELECT rmlp.attachment FROM ' . $table_name_lrsync . ' lr
            INNER JOIN ' . $table_name_posts . ' rmlp ON rmlp.isShortcut = lr.wp_id
            WHERE rmlp.isShortcut > 0';
        $ids = $wpdb->get_col($sql);
        foreach ($ids as $id) {
            wp_delete_attachment($id, true);
        }
        
        return new \WP_REST_Response(true);
    }
    
    /**
     * Get the wp-json URL for a defined REST service.
     * 
     * @param string $namespace The prefix for REST service
     * @param string $endpoint The path appended to the prefix
     * @returns String Example: https://example.com/wp-json
     * @example Service::url(Service::SERVICE_NAMESPACE) // => main path
     */
    public static function getUrl($namespace, $endpoint = '') {
        return site_url(rest_get_url_prefix()) . '/' . $namespace . '/' . $endpoint;
    }
}