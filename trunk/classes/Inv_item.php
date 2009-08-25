<?php
/**
 * Inventory Item
 */
 
 class Inv_item extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_item');    
    }
	
	static function findByClassificationCode($key)
	{
		return fRecordSet::build('Inv_item',
			array('classification_code=' => $key));
	}
	
	static function findBySubCategoryCode($key)
	{
		return fRecordSet::build('Inv_item',
			array('sub_category_code=' => $key));
	}
	
	static function findByMainCategoryCode($key)
	{
		return fRecordSet::build('Inv_item',
			array('main_category_code=' => $key));
	}
 }

?>