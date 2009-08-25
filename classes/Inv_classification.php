<?php
/**
 * Inventory Classification
 */
 
 class Inv_classification extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all meetups
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_classification');    
    }
	
	static function findBySubCategoryCode($key)
	{
		return fRecordSet::build('Inv_classification',
			array('sub_category_code=' => $key));
	}
	
	static function findOptionBySubCategoryCode($key)
	{
		$tempRecords = self::findBySubCategoryCode($key);
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareDescription()." [".$tempRecord->prepareClassificationCode()."]",$tempRecord->prepareId());
	}

 }

?>