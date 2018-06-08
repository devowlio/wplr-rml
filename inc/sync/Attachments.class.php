<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\sync;
use MatthiasWeb\RealMediaLibrary\WPLR\base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * This class handles the sync with WP/LR extension API when sync attachments to collections.
 */
class Attachments extends base\Base {
    
    /**
     * An array representing a key-value map of the RML fid => WP/LR wp_col_id
     * to reindex when media is published to the collection. This isn't necessery
     * in remove action.
     */
    private $batchIndexSort = array();
    
    /**
     * All the stuff is done for the WP/LR synchronization, let's reflect the
     * wp_lrsync_relations.sort to wp_realmedialibrary_posts.nr.
     */
    public function rml_die() {
        global $wpdb;
        $indexes = array_unique($this->batchIndexSort);
        if (count($indexes) > 0) {
            foreach ($indexes as $rmlFolder => $colId) {
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
                WHERE rmlp.fid = %d", $colId, $rmlFolder);
                $wpdb->query($sql);
            }
        }
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
        
        // Create shortcut in the given collection if not already exists
        $rmlFolder = Folders::wplr2rml($collectionId, true);
        if (!wp_attachment_has_shortcuts($mediaId, $rmlFolder)) {
            $create = wp_rml_create_shortcuts($rmlFolder, array($mediaId), true);
            if (is_array($create)) {
    			wp_die('Error while creating shortcut to collection: ' . $create[0]);
    		}
    		$this->batchIndexSort[$rmlFolder] = Folders::wplr2rml($collectionId)->getRowData('wplr_id');
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
        }else{
            // The attachment is only in max one collection left, remove the original file with shortcuts...
            //wp_delete_attachment($mediaId, true); This is automatically done by WP/LR
        }
	}
}