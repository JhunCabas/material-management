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

	static function findCurrentMonth($branch)
	{		
		return fRecordSet::buildFromSQL('Good_receipt_note',
				"SELECT good_receipt_notes.* FROM good_receipt_notes WHERE good_receipt_notes.branch_id = '$branch' 
				AND YEAR( good_receipt_notes.doc_date ) = YEAR( CURDATE( )) AND MONTH( good_receipt_notes.doc_date ) = MONTH( CURDATE( ))",
				"SELECT count(*) FROM good_receipt_notes"
			);			
	}
 }

?>