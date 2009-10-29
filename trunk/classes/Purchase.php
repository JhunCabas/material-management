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
			array('doc_date' => 'desc'),
			$limit);
	}

	static function findUncompletePO($limit=null)
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'po', 'status!' => 'completed'),
			array('doc_date' => 'desc'),
			$limit);

	}
	
	static function findByBranch($branch,$doctype)
	{
		return fRecordSet::buildFromSQL('Purchase',
				"SELECT purchases.* FROM purchases, (SELECT * FROM users WHERE branch_id = '$branch') AS tbl WHERE purchases.requester = tbl.username AND purchases.doc_type = '$doctype'",
				"SELECT count(*) FROM users"
			);
		//return fRecordSet::build('Purchase',
		//	array('branch_id=' => $branch));
		
	}
	
	static function findAllPR($limit=null)
	{
		return fRecordSet::build('Purchase',
			array('doc_tag=' => 'pr'),
			array('doc_date' => 'desc'),
			$limit);
	}
	
	static function findByDocType($key)
	{
		return fRecordSet::build('Purchase',
			array('doc_type=' => $key));
	}
 }

?>