<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\sync;
use MatthiasWeb\RealMediaLibrary\WPLR\base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * This class handles the sync with WP/LR extension API when sync attachments to collections.
 */
class Attachments extends base\Base {
    public function add_to_collection($mediaId, $collectionId) {
        global $wplr;
        
        // Check attachments location
        $rmlRoot = Folders::wplr2rml(-1, true);
        $mediaRmlFolder = wp_attachment_folder($mediaId);
        if ($mediaRmlFolder === null || (int) $mediaRmlFolder !== $rmlRoot) {
            // Move the attachment to root
            $move = wp_rml_move($rmlRoot, array($mediaId), true);
            if (is_array($move)) {
    			wp_die('Error while moving attachment to root: ' . $move[0]);
    		}
        }
        
        // Create shortcut in the given collection if not already exists
        $rmlFolder = Folders::wplr2rml($collectionId, true);
        if (!wp_attachment_has_shortcuts($mediaId, $rmlFolder)) {
            $create = wp_rml_create_shortcuts($rmlFolder, array($mediaId), true);
            if (is_array($create)) {
    			wp_die('Error while creating shortcut to collection: ' . $create[0]);
    		}
        }
    }
    
    // @TODO update count cache
    public function remove_from_collection($mediaId, $collectionId) {
        // Get shortcuts and delete these
        $rmlFolder = Folder::wplr2rml($collectionId, true);
        $shortcuts = wp_attachment_get_shortcuts($mediaId, $rmlFolder);
        foreach ($shortcuts as $shortcutId) {
            wp_delete_attachment($shortcutId, true);
        }
	}
}