<?php
/**
 * Inventory Sub Category
 */
 
 class Inv_subcategory extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all meetups
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_subcategory');    
    }
	
	static function findOptionByMainCategoryCode($key)
	{
		$tempRecords = self::findByMainCategoryCode($key);
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareDescription()." [".$tempRecord->prepareCategoryCode()."]",$tempRecord->prepareId());
	}
	
	static function findByMainCategoryCode($key)
	{
		return fRecordSet::build('Inv_subcategory',
			array('main_category_code=' => $key));
	}
 }

?>