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
	static function findAll($limit=null)
	{
		return fRecordSet::build('Production_issue',null,
			array('doc_date' => 'desc'),
			$limit); 
	}
 }

?>