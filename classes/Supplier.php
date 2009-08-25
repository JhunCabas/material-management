<?php
/**
 * Supplier
 */
 
 class Supplier extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Supplier');    
    }
 }

?>