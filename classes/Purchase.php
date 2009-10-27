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
	
	static function findByBranch($branch)
	{
		return fRecordSet::build('Purchase',
			array('branch_id=' => $branch));
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