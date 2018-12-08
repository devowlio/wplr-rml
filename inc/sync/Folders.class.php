<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\sync;
use MatthiasWeb\RealMediaLibrary\WPLR\base;
use MatthiasWeb\RealMediaLibrary\WPLR\general;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); // Avoid direct file request

/**
 * This class handles the sync with WP/LR extension API when sync collections and folders.
 */
class Folders extends base\Base {
    private static $ROOT_NAME = 'WPLR Sync';
    
	private static $RESTRICTIONS_ROOT = array('ren>', 'cre>', 'ins>', 'del>', 'mov>');
	
	private static $RESTRICTIONS = array('par>');
	
    /**
     * A new folder is created in RML so check if it is the root folder and
     * initially add some folder metadata.
     */
    public function folder_created($parent, $name, $type, $id) {
        if ($type === WPLR_RML_TYPE_ROOT) {
            // Make compatible with WP Real Physical Media
            add_media_folder_meta($id, 'rpmPhysicalExcludeFolder', true, true);
        }
    }
    
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
        
        wp_rml_get_object_by_id($id)->setWplrId(-1);
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
		wp_rml_get_object_by_id($id)->setWplrId($folderId);
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
		wp_rml_get_object_by_id($id)->setWplrId($collectionId);
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
    
    public function move_collection($collectionId, $folderId, $previousFolderId) {
        // Default InFolder should be "WPLR Sync"
		if ($collectionId != -1 && empty($folderId)) {
            $parent = $this->create_root();
            $inFolderId = -1;
		} else {
		    $parent = self::wplr2rml($folderId, true);
		}
		
		self::wplr2rml($collectionId)->setParent($parent, -1, true);
    }
    
    public function move_folder($folderId, $inFolderId, $previousFolderId) {
        // Default InFolder should be "WPLR Sync"
		if ($folderId != -1 && empty($inFolderId)) {
            $parent = $this->create_root();
            $inFolderId = -1;
		} else {
		    $parent = self::wplr2rml($inFolderId, true);
		}
		
		self::wplr2rml($folderId)->setParent(empty($parent) ? -1 : $parent, -1, true);
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