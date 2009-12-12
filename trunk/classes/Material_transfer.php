<?php
/**
 * Material Transfer
 */
 
 class Material_transfer extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll($limit=null)
    {
        return fRecordSet::build('Material_transfer',null,
			array('doc_date' => 'desc'),
			$limit);       
    }

	static function findAllUncomplete($limit=null)
	{
		return fRecordSet::build('Material_transfer',
			array('status=' => 'pending'),
			array('doc_date' => 'desc'),
			$limit);
	}
	
	static function findByBranchFrom($limit=null,$branch)
	{
		return fRecordSet::build('Material_transfer',
			array('status=' => 'pending','branch_from=' => $branch),
			array('doc_date' => 'desc'),
			$limit);
	}
	
	static function findByBranchTo($limit=null,$branch)
	{
		return fRecordSet::build('Material_transfer',
			array('status=' => 'pending','branch_to=' => $branch),
			array('doc_date' => 'desc'),
			$limit);
	}
	
	static function findAllComplete($limit=null)
	{
		return fRecordSet::build('Material_transfer',
			array('status=' => 'completed'),
			array('doc_date' => 'desc'),
			$limit);
	}
	
 }

?>