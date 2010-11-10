<?php
/**
 * 
 *
 * @author Shern Shiou Tan
 * @version 1.00
 * @copyright Pocketzila, 11 November, 2010
 * @package MMS
 **/

/**
 * Inventory Movements
 **/

class Inv_movement extends fActiveRecord
{
	/**
	 * Return all entries
	 *
	 * @return 	fRecordSet	An object containing all entries
	 */
	static function findAll()
	{
		return fRecordSet::build('Inv_movement'); 
	}
}

?>