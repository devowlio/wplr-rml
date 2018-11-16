<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\folder;
use MatthiasWeb\RealMediaLibrary\attachment;
use MatthiasWeb\RealMediaLibrary\order;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * This class creates a WPLR collection. (Type 12)
 * See parent classes / interfaces for better documentation.
 */
class Collection extends order\Sortable {
    
    use BaseFolder;
    
    public static function create($rowData) {
        $result = new Collection($rowData->id);
        $result->setParent($rowData->parent);
        $result->setName($rowData->name, $rowData->supress_validation);
        $result->setRestrictions($rowData->restrictions);
        return $result;
    }
    
    public static function instance($rowData) {
        return new Collection($rowData->id, $rowData->parent, $rowData->name, $rowData->slug, $rowData->absolute, 
                            $rowData->ord, $rowData->cnt_result, $rowData);
    }

    public function getAllowedChildrenTypes() {
        return array();
    }
    
    public function getType() {
        return WPLR_RML_TYPE_COLLECTION;
    }
    
    public function getContentCustomOrder() {
        return "2";
    }
    
    public function forceContentCustomOrder() {
        return true;
    }
    
    public function getTypeName($default = null) {
        return parent::getTypeName($default === null ? __('WP/LR Collection', WPLR_RML_TD) : $default);
    }
    
    public function getTypeDescription($default = null) {
        return parent::getTypeDescription($default === null ? __('Synchronized WP/LR collection containing your files.', WPLR_RML_TD) : $default);
    }
    
}

?>