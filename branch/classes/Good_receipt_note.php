<?php
/**
 * Good Receipt Notes
 */
 
 class Good_receipt_note extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll($limit=null)
    {
        return fRecordSet::build('Good_receipt_note',null,
			array('doc_date' => 'desc'),
			$limit);    
    }
 }

?>