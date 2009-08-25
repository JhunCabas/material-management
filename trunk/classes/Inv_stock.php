<?php
/**
 * Inventory Stock
 */
 
 class Inv_stock extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_stock');    
    }

	static function findByBranch($key)
	{
		return fRecordSet::build('Inv_stock',
			array('branch_id=' => $key));
	}
}