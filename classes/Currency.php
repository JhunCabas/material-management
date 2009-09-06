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
	
	static function findCurrentMonth($month, $year)
	{
		return fRecordSet::buildFromSQL(
	    	'Currency',
	    	"SELECT currencies.* FROM currencies WHERE YEAR(month) = $year AND MONTH(month) = $month"
		);
	}
	
	static function findCurrentMonthOption($month, $year)
	{
		$tempRecords = self::findCurrentMonth($month, $year);
		foreach($tempRecords as $tempRecord)
			fHTML::printOption($tempRecord->prepareCountry()." [".$tempRecord->prepareExchange(2)."]",$tempRecord->prepareId(),null);
	}

 }

?>