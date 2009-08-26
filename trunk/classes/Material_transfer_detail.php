<?php
/**
 * Material Transfer Detail
 */
 
 class Material_transfer_detail extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findDetail($key)
	{
		return fRecordSet::build('Material_transfer_detail',
			array('doc_number=' => $key));
	}

 }

?>