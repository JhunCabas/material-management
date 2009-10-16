<?php
/**
 * Good Receipt Note Detail
 */
 
 class Good_receipt_note_detail extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findDetail($key)
	{
		return fRecordSet::build('Good_receipt_note_detail',
			array('doc_number=' => $key));
	}
	
	protected function configure()
	{
		fORMJSON::extend();
	}
 }

?>