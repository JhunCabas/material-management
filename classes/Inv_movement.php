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
	
	/**
	 * Return entries by month and branch
	 *
	 * @param string 	$branch	Branch ID to represent a branch 
	 * @param int		$month	Specific month as the input
	 * @param string	$item	Item code to specify an individual item
	 * @return  int	The total movement by month
	 */
	static function findByMonth($branch, $month, $item)
	{
		$movements = fRecordSet::build('Inv_movement',
			array('branch_id=' => $branch,
			'date>=' => ('2010-'.$month.'-1'),'date<=' => ('2010-'.$month.'-31'),
			'item_id=' => $item));
		if($movements->count())
			return $movements->getRecord(0)->prepareQuantity(); 
		else
			return 0;
	}
}

?>