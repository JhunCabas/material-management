<?php
/**
 * Purchase Request and Purchase Order
 */
 
 class Purchase extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Purchase');    
    }
	
	static function findAllPO($limit=null)
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'po'),
			array('approver_1_date' => 'desc'),
			$limit);
	}
	
	static function findOpenPO($limit=null)
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'po','status!' => 'cancelled'),
			array('approver_1_date' => 'desc'),
			$limit);
	}

	static function findAllUncompletePO($limit=null)
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'po', 'status!' => 'completed'),
			array('doc_date' => 'desc'),
			$limit);

	}
	
	static function findByBranch($branch,$doctype)
	{
		return fRecordSet::buildFromSQL('Purchase',
				"SELECT purchases.* FROM purchases, (SELECT * FROM users WHERE branch_id = '$branch') AS tbl WHERE purchases.requester = tbl.username AND purchases.doc_type = '$doctype' AND YEAR( purchases.doc_date ) = YEAR( CURDATE( ) ) AND MONTH( purchases.doc_date ) = MONTH( CURDATE( ) )",
				"SELECT count(*) FROM purchases"
			);
	}
	
	static function findPOByBranch($branch,$doctype)
	{
		return fRecordSet::buildFromSQL('Purchase',
				"SELECT purchases.* FROM purchases, (SELECT * FROM users WHERE branch_id = '$branch') AS tbl WHERE purchases.requester = tbl.username AND purchases.doc_tag = 'po' AND purchases.doc_type = '$doctype' AND YEAR( purchases.po_date ) = YEAR( CURDATE( ) ) AND MONTH( purchases.po_date ) = MONTH( CURDATE( ) )",
				"SELECT count(*) FROM purchases"
			);
	}
	
	static function findAllPR($limit=null)
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'pr', 'status!' => 'cancelled'),
			array('doc_date' => 'desc'),
			$limit);
	}
	
	static function findByDocType($key)
	{
		return fRecordSet::build('Purchase',
			array('doc_type=' => $key));
	}
	
	static function getByPoNumber($key)
	{
		$records = fRecordSet::build('Purchase',
			array('po_number=' => $key));
		if($records->count())
		{
			$i = 0;
			foreach ($records as $record) {
				if ( $i == 1 )
				break;
				return $record->getDocNumber();
				$i++;
			}
		}
		else
			return 0;
	}
 }

?>