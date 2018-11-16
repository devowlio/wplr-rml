<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\folder;
use MatthiasWeb\RealMediaLibrary\attachment;
use MatthiasWeb\RealMediaLibrary\order;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * This class creates a root object for WPLR. (Type 10)
 * See parent classes / interfaces for better documentation.
 */
class Root extends order\Sortable {
    
    use BaseFolder;
    
    public static function create($rowData) {
        $result = new Root($rowData->id);
        $result->setParent($rowData->parent);
        $result->setName($rowData->name, $rowData->supress_validation);
        $result->setRestrictions($rowData->restrictions);
        return $result;
    }
    
    public static function instance($rowData) {
        return new Root($rowData->id, $rowData->parent, $rowData->name, $rowData->slug, $rowData->absolute, 
                            $rowData->ord, $rowData->cnt_result, $rowData);
    }

    public function getAllowedChildrenTypes() {
        return array(WPLR_RML_TYPE_COLLECTION, WPLR_RML_TYPE_FOLDER);
    }
    
    public function getType() {
        return WPLR_RML_TYPE_ROOT;
    }
    
    public function getContentCustomOrder() {
        return "2";
    }
    
    public function getTypeName($default = null) {
        return parent::getTypeName($default === null ? __('WP/LR', WPLR_RML_TD) : $default);
    }
    
    public function getTypeDescription($default = null) {
        return parent::getTypeDescription($default === null ? __('This is the root folder for the synchronized Lightroom collections and folders.', WPLR_RML_TD) : $default);
    }
    
}

?>