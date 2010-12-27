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

	static function findCurrentMonth($branch)
	{
		return fRecordSet::buildFromSQL('Material_transfer',
				"SELECT material_transfers.* FROM material_transfers WHERE material_transfers.branch_id = '$branch' 
				AND YEAR( material_transfers.doc_date ) = YEAR( CURDATE( )) AND MONTH( material_transfers.doc_date ) = MONTH( CURDATE( ))",
				"SELECT count(*) FROM material_transfers"
			);
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
	
	static function findByBranchToFro($branch,$limit=null)
	{
		return fRecordSet::buildFromSQL('Material_transfer',
			"SELECT material_transfers.* FROM material_transfers WHERE `status` = 'pending' AND (`branch_to` = '$branch' OR `branch_from` = '$branch')",
			"SELECT count(*) FROM material_transfers");
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