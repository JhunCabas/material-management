<?php
/**
 * Production Issue
 */
 
 class Production_issue extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Production_issue');    
    }
 }

?>