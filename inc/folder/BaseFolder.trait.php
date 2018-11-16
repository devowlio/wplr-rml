<?php
namespace MatthiasWeb\RealMediaLibrary\WPLR\folder;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Common methods for WP/LR folders.
 */
trait BaseFolder {
    /**
     * Set the wplr_id column in database.
     * 
     * @param int $id The wplr id
     * @returns boolean
     */
    public function setWplrId($id) {
        global $wpdb;
        $sql = 'UPDATE ' . $this->getTableName() . ' SET wplr_id=%d WHERE id = %d';
        $wpdb->query($wpdb->prepare($sql, $id, $this->getId()));
        
        if (is_object($this->row)) {
            $this->row->wplr_id = $id;
            return true;
        }else{
            return false;
        }
    }
}

?>