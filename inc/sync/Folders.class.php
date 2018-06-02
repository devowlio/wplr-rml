<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\sync;
use MatthiasWeb\RealMediaLibrary\WPLR\base;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * This class handles the sync with WP/LR extension API when sync collections and folders.
 * 
 * @TODO migration for old extension
 */
class Folders extends base\Base {
    private static $ROOT_NAME = 'WPLR Sync';
    
	private static $RESTRICTIONS_ROOT = array('ren>', 'cre>', 'ins>', 'del>', 'mov>');
	
	private static $RESTRICTIONS = array('par>');
    
    /**
     * Create the root folder if not already exists.
     * 
     * @returns int
     */
    public function create_root() {
        // Already exists
        $rmlFolder = self::wplr2rml(-1, true);
        if ($rmlFolder !== null) {
            return $rmlFolder;
        }
        
        // Create
        $id = wp_rml_create_or_return_existing_id(self::$ROOT_NAME, _wp_rml_root(), WPLR_RML_TYPE_ROOT, self::$RESTRICTIONS_ROOT);
        if (is_array($id)) {
            wp_die('Could not create Lightroom root folder');
        }
        
        $this->setWplrId($id, -1);
        return $id;
    }
    
    public function reset() {
		$this->remove_folder(-1);
	}
    
    public function create_folder($folderId, $inFolderId, $folder) {
		// Already exists
		$rmlFolder = self::wplr2rml($folderId, true);
		if ($rmlFolder !== null) {
		    return $rmlFolder;
		}

		// Default InFolder should be "WPLR Sync"
		if ($folderId != -1 && empty($inFolderId)) {
			$parent = $this->create_root();
			$inFolderId = -1;
		} else {
			$parent = self::wplr2rml($inFolderId, true);
			if ($parent === null) {
			    wp_die(sprintf('Could not find folder parent with id %d of %d.', $inFolderId, $folderId));
			}
		}
		
		// Create
		$id = wp_rml_create_or_return_existing_id($folder['name'], $parent, WPLR_RML_TYPE_FOLDER, self::$RESTRICTIONS, true);
		if (is_array($id)) {
			wp_die('Error while creating folder: ' . $id[0]);
		}
		$this->setWplrId($id, $folderId);
		return $id;
	}
    
    public function create_collection($collectionId, $inFolderId, $collection) {
		if (empty($inFolderId)) {
			$this->create_root();
			$inFolderId = -1;
		}
		
		$parent = self::wplr2rml($inFolderId, true);
		$id = wp_rml_create_or_return_existing_id($collection['name'], $parent, WPLR_RML_TYPE_COLLECTION, self::$RESTRICTIONS, true);
		if (is_array($id)) {
		    wp_die('Error while creating collection: ' . $id[0]);
		}
		$this->setWplrId($id, $collectionId);
		return $id;
    }
    
    public function remove_folder($folderId) {
        $rmlFolder = self::wplr2rml($folderId, true);
        wp_rml_delete($rmlFolder, true);
    }
    
    public function remove_collection($collectionId) {
        $this->remove_folder($collectionId);
    }
    
    public function update_folder($folderId, $folder) {
        $rmlFolder = self::wplr2rml($folderId, true);
        wp_rml_rename($folder['name'], $rmlFolder, true);
    }
    
    public function update_collection($collectionId, $collection) {
        $this->update_folder($collectionId, $collection);
    }
    
    /**
     * Set the wplr_id for a given folder.
     * 
     * @param int $folderId The RML folder id
     * @param int $id The WPLR id
     */
    public function setWplrId($folderId, $id) {
        global $wpdb;
        
        $sql = 'UPDATE ' . $this->getTableName('', true) . ' SET wplr_id=%d WHERE id = %d';
        return $wpdb->query($wpdb->prepare($sql, $id, $folderId));
    }
    
    /**
     * Get the folder object from a WPLR id from the RML parsed structure.
     * 
     * @param int $id The WPLR intern id
     * @returns IFolder
     */
    public static function wplr2rml($id, $asId = false) {
        global $wpdb;
        
        $folders = wp_rml_structure()->getParsed();
        foreach ($folders as $folder) {
            $wplrId = $folder->getRowData('wplr_id');
            if ($wplrId !== null && (int) $wplrId === $id) {
                return $asId ? $folder->getId() : $folder;
            }
        }
        return null;
    }
}