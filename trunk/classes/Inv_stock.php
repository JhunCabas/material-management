<?php
/**
 * Inventory Stock
 */
 
 class Inv_stock extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_stock');    
    }

	static function findByBranch($key)
	{
		return fRecordSet::build('Inv_stock',
			array('branch_id=' => $key));
	}
	
	static function findByStock($item_id, $quantity)
	{
		return fRecordSet::build('Inv_stock',
			array('item_id=' => $item_id , 'quantity>=' => $quantity));
	}
	
	static function gotoStock($item_id, $branch)
	{
		return fRecordSet::build('Inv_stock',
			array('item_id=' => $item_id, 'branch_id=' => $branch));
	}
	
	static function addStock($item, $branch, $quantity)
	{
		$records = fRecordSet::build('Inv_stock',
			array('item_id=' => $item , 'branch_id=' => $branch));
		if($records->count())
		{
			foreach($records as $record)
			{
				$record->setQuantity($record->getQuantity() + $quantity);
				$record->store();
			}
		}else{
			$record = new self();
			$record->setBranchId($branch);
			$record->setItemId($item);
			$record->setQuantity($quantity);
			$record->store();
		}
	}
	
	static function removeStock($item, $branch, $quantity)
	{
		$records = fRecordSet::build('Inv_stock',
			array('item_id=' => $item , 'branch_id=' => $branch));
		if($records->count())
		{
			foreach($records as $record)
			{
				$record->setQuantity($record->getQuantity() - $quantity);
				$record->store();
			}
		}else{
			echo "Error";
		}
	}
}