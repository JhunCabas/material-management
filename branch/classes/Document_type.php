<?php
/**
 * Document Type
 */
 
 class Document_type extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Document_type');    
    }

	static function findAllOption()
	{
		$tempRecords = self::findAll();
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareId(), $tempRecord->prepareId());
	}
 }

?>