<?php
/**
 * Material Transfer
 */
 
 class Material_transfer extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Material_transfer');    
    }
 }

?>