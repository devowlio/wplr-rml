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
        ) AS rmlnew ON (rmlp.isShortcut = rmlnew.attachment OR rmlp.attachment = rmlnew.attachment)
        SET rmlp.nr = rmlnew.nr
        WHERE rmlp.fid = %d", $rmlFolder->getRowData('wplr_id'), $rmlFolder->getId());
        $wpdb->query($sql);
    }
    
    public function add_to_collection($mediaId, $collectionId) {
        global $wplr;
        $rmlFolder = Folders::wplr2rml($collectionId);
        $fid = $rmlFolder->getId();
        $currentFolder = (int) wp_attachment_folder($mediaId);
        $sort = false;
        
        // Check if first occurance then move the file
        if ($currentFolder === _wp_rml_root() /* Should this check if it is not a WP_LR folder? */) {
            $resp = wp_rml_move($fid, array($mediaId), true);
            if (is_array($resp)) {
                wp_die('Error while adding media (' . $mediaId . ') to collection (' . $collectionId . ' -> RML: ' . $fid . '): ' . $resp[0]);
            }
            $sort = true;
        }else if ($currentFolder !== $fid && !wp_attachment_has_shortcuts($mediaId, $fid)) { // Create shortcut in the given collection if not already exists
            $create = wp_rml_create_shortcuts($fid, array($mediaId), true);
            if (is_array($create)) {
                wp_die('Error while creating shortcut for (' . $mediaId . ') to collection (' . $collectionId . ' -> RML: ' . $fid . '): ' . $create[0]);
            }
            $sort = true;
        }
        
        // Sort the folder to the LR custom order
        if ($sort) {
            $this->syncSort($rmlFolder);
        }
    }
    
    public function remove_from_collection($mediaId, $collectionId) {
        $rmlFolder = Folders::wplr2rml($collectionId, true);
        $shortcuts = wp_attachment_get_shortcuts($mediaId, $rmlFolder, true);
        if (count($shortcuts) > 0) {
            // It's a shortcut which gets deleted, so delete it directly
            foreach ($shortcuts as $shortcut) { 
                if ((int) $shortcut['folderId'] === $rmlFolder) { 
                    wp_delete_attachment((int) $shortcut['attachment'], true); 
                }
            }
        }else{
            // Check if there are any further shortcuts
            $sto = _wp_rml_root();
            $shortcuts = wp_attachment_get_shortcuts($mediaId, false, true);
            if (count($shortcuts) > 0) {
                // There exists another shortcut, so delete the first one and move the file
                wp_delete_attachment($shortcuts[0]['attachment'], true);
                $sto = $shortcuts[0]['folderId'];
            }else{
                // It is the default image so move it to unorganized, Silence is golden.
            }
            wp_rml_move($sto, array($mediaId), true);
        }
    }
    
    /**
     * Real Physical Media compatibility to allow instant process when a file got
     * synchronized through WP/LR.
     */
    public function rpm_instant_process($process, $processId) {
        global $wpdb;
        if ($process !== true) {
            // Read the affected folders (only one because Lightroom)
            $table_name_rpm = $wpdb->prefix . 'realphysicalmedia_queue';
            $table_name_rml = $this->getTableName('', true);
            $table_name_p = $this->getTableName('posts', true);
            $sql = $wpdb->prepare('SELECT DISTINCT rml.id FROM ' . $table_name_rpm . ' rpm
                INNER JOIN ' . $table_name_p . ' rmlp ON rpm.attachment = rmlp.attachment
                INNER JOIN ' . $table_name_rml . ' rml ON rmlp.fid = rml.id
                WHERE processId = %s', $processId);
            $ids = $wpdb->get_col($sql);
            
            // Check if any of the destination folders have WP/LR as parent
            foreach ($ids as $fid) {
                $obj = wp_rml_get_object_by_id($fid);
                if ($obj !== null) {
                    $parentHas = $obj->anyParentHas('wplr_id', -1, '%d', true);
                    if (count($parentHas) > 0) {
                        return true;
                    }
                }
            }
        }
        return $process;
    }
}