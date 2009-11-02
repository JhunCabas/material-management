<?php
/**
 * Inventory Main Category
 */
 
 class Inv_maincategory extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_maincategory');    
    }
	
	static function findAllOption()
	{
		$tempRecords = self::findAll();
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareDescription()." [".$tempRecord->prepareCategoryCode()."]",$tempRecord->prepareCategoryCode());
	}

 }

?>