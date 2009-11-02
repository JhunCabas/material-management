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
	
	protected function configure()
	{
		fORMJSON::extend();
	}
 }

?>