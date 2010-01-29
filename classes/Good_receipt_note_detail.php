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
	
	static function deleteDetail($key)
	{
		try{
			$detail = new Good_receipt_note_detail($key);
			$detail->delete();
		} catch (fValidationException $e) {
		    echo $e->printMessage();
		}
	}
	
	protected function configure()
	{
		fORMJSON::extend();
	}
 }

?>