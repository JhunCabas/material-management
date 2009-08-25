<?php
/**
 * Branch
 */
 
 class Branch extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Branch');    
    }

	static function findAllOption($default = null)
	{
		$tempRecords = self::findAll();
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareName(),$tempRecord->prepareId(), $default);
	}

 }

?>