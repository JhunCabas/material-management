<?php
/**
 * Production Issue Detail
 */
 
 class Production_issue_detail extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findDetail($key)
	{
		return fRecordSet::build('Production_issue_detail',
			array('doc_number=' => $key));
	}

 }

?>