<?php
/**
 * Purchase Request and Purchase Order
 */
 
 class Purchase extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Purchase');    
    }

	static function findAllPO()
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'po'));
	}
	
	static function findAllPR()
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'pr'));
	}
 }

?>