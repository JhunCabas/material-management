<?php
/**
 * Purchase Document Detail
 */
 
 class Purchase_detail extends fActiveRecord
 {
	/**
     * Returns all entries from Document Number
     * 
     * @return fRecordSet  An object containing all entries from a Document Number
     */

	static function findDetail($key)
	{
		return fRecordSet::build('Purchase_detail',
			array('doc_number=' => $key));
	}
	
	static function deleteDetail($key)
	{
		try{
			$detail = new Purchase_detail($key);
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