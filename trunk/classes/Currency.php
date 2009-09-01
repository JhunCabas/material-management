<?php
/**
 * Currency
 */
 
 class Currency extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Currency');    
    }

	static function findAllOption($default = null)
	{
		$tempRecords = self::findAll();
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareCountry(),$tempRecord->prepareId(), $default);
	}

 }

?>