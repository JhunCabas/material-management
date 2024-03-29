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
			array('branch_id=' => $key),
			array('item_id' => 'asc'));
	}
	
	static function findByBranchLimit($key,$first = 0,$number = 500)
	{
		return fRecordSet::buildFromSQL('Inv_stock',
			"SELECT * FROM inv_stocks WHERE `branch_id` = '$key' ORDER BY `item_id` ASC LIMIT $first , $number",
			"SELECT count(*) FROM inv_stocks WHERE `branch_id` = '$key'");
	}
	
	static function findByItem($key)
	{
		return fRecordSet::build('Inv_stock',
			array('item_id=' => $key),
			array('item_id' => 'asc'));
	}
	
	static function findByStock($item_id, $quantity)
	{
		return fRecordSet::build('Inv_stock',
			array('item_id=' => $item_id , 'quantity>=' => $quantity));
	}
	
	static function findStockByBranch($item, $branch)
	{
		return fRecordSet::build('Inv_stock',
			array('item_id=' => $item, 'branch_id=' => $branch));
	}
	
	static function gotoStock($item_id, $branch)
	{
		return fRecordSet::build('Inv_stock',
			array('item_id=' => $item_id, 'branch_id=' => $branch));
	}
	
	static function transitStock($item, $branch, $quantity)
	{
		$records = fRecordSet::build('Inv_stock',
			array('item_id=' => $item, 'branch_id=' => $branch));
		if($records->count())
		{
			foreach($records as $record)
			{
				$record->setQuantity($record->getQuantity() - $quantity);
				$record->setTransit($record->getTransit() + $quantity);
				$record->store();
			}
		}else{
			echo "Unexpected Error in Quantity";
		}
	}
	
	static function rejectTransit($item, $branch, $quantity)
	{
		$records = fRecordSet::build('Inv_stock',
			array('item_id=' => $item, 'branch_id=' => $branch));
		if($records->count())
		{
			foreach($records as $record)
			{
				$record->setQuantity($record->getQuantity() + $quantity);
				$record->setTransit($record->getTransit() - $quantity);
				$record->store();
			}
		}else{
			echo "Unexpected Error in Quantity";
		}
	}
	
	static function moveTransit($item, $branchFrom, $branchTo, $quantity)
	{
		$records = fRecordSet::build('Inv_stock',
			array('item_id=' => $item, 'branch_id=' => $branchFrom));
		if($records->count())
		{
			foreach($records as $record)
			{
				$record->setTransit($record->getTransit() - $quantity);
				self::addStock($item,$branchTo,$quantity);
				$record->store();
			}
		}else{
			echo "Unexpected Error in Quantity";
		}
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
	
	static function resetStock($id,$quantity)
	{
		try{
			
			$record = new self($id);
			$previous_q = $record->getQuantity();
			$record->setQuantity($quantity);
			$record->store();
			
			$movement = new Inv_movement();
			$movement->setItemId($record->getItemId());
			$movement->setBranchId($record->getBranchId());
			$movement->setDocumentNumber("Reset");
			$movement->setQuantity($quantity - $previous_q);
			$movement->setDate(date('n/j/Y'));
			$movement->store();
		} catch (fExpectedException $e) {
			echo $e->printMessage();
		}
		echo $record->getQuantity();
	}
}