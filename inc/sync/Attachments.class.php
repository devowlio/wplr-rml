<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\sync;
use MatthiasWeb\RealMediaLibrary\WPLR\base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * This class handles the sync with WP/LR extension API when sync attachments to collections.
 */
class Attachments extends base\Base {
    
    private function syncSort($rmlFolder) {
        global $wpdb;
        $table_name_posts = $this->getTableName('posts', true);
        $table_name_relations = $this->getTableName('relations', 'wplr');
        $sql = $wpdb->prepare("UPDATE $table_name_posts AS rmlp
        LEFT JOIN (
        	SELECT lrr.wp_id AS attachment, @rownum := @rownum + 1 AS nr
        	FROM (SELECT @rownum := 0) AS r, $table_name_relations lrr
        	WHERE lrr.wp_col_id = %d
        	ORDER BY lrr.sort
        ) AS rmlnew ON rmlp.isShortcut = rmlnew.attachment
        SET rmlp.nr = rmlnew.nr
        WHERE rmlp.fid = %d", $rmlFolder->getRowData('wplr_id'), $rmlFolder->getId());
        $wpdb->query($sql);
    }
    
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
        
        $rmlFolder = Folders::wplr2rml($collectionId);
        if (is_rml_folder($rmlFolder)) {
            $fid = $rmlFolder->getId();
            
            // Create shortcut in the given collection if not already exists
            if (!wp_attachment_has_shortcuts($mediaId, $fid)) {
                $create = wp_rml_create_shortcuts($fid, array($mediaId), true);
                if (is_array($create)) {
        			wp_die('Error while creating shortcut to collection: ' . $create[0]);
        		}
            }
            
            // Sync sort
            $this->syncSort($rmlFolder);
        }
    }
    
    public function remove_from_collection($mediaId, $collectionId) {
        // Get shortcuts and delete them
        $rmlFolder = Folders::wplr2rml($collectionId, true);
        $shortcuts = wp_attachment_get_shortcuts($mediaId, false, true);
        
        if (count($shortcuts) > 1) {
            // The attachment is in more than one collection, so do not delete original file...
            foreach ($shortcuts as $shortcut) {
                if ((int) $shortcut['folderId'] === $rmlFolder) {
                    wp_delete_attachment((int) $shortcut['attachment'], true);
                }
            }
        }
	}
}