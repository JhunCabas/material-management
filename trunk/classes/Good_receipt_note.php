<?php
/**
 * Good Receipt Notes
 */
 
 class Good_receipt_note extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll($limit=null)
    {
        return fRecordSet::build('Good_receipt_note',null,
			array('doc_date' => 'desc'),
			$limit);    
    }
	
	static function findAllByBranch($branch=null,$limit=null)
    {
        return fRecordSet::build('Good_receipt_note',
			array('branch_id=' => $branch),
			array('doc_date' => 'desc'),
			$limit);    
    }

	static function findCurrentMonth($branch)
	{		
		return fRecordSet::buildFromSQL('Good_receipt_note',
				"SELECT good_receipt_notes.* FROM good_receipt_notes WHERE good_receipt_notes.branch_id = '$branch' AND good_receipt_notes.doc_type = 'GRN' AND YEAR( good_receipt_notes.doc_date ) = YEAR( CURDATE( )) AND MONTH( good_receipt_notes.doc_date ) = MONTH( CURDATE( ))",
				"SELECT count(*) FROM good_receipt_notes"
			);			
	}
	
	static function findRev($po)
	{
		$records = fRecordSet::build('Good_receipt_note',
			array('po_no=' => '$po'));
		
		return $records->count() + 1;
	}

	static function findStatus($po)
	{
		$records = fRecordSet::build('Good_receipt_note', array('po_no=' => $po));
		if($records->count() > 1) 
			return false;
		else
			return true;
	}
 }

?>