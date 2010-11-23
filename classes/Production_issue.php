<?php
/**
 * Production Issue
 */
 
 class Production_issue extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
	static function findAll($limit=null)
	{
		return fRecordSet::build('Production_issue',
			array('status!' => 'cancelled'),
			array('doc_date' => 'desc'),
			$limit); 
	}
	
	static function findByBranch($branch,$doctype)
	{
		return fRecordSet::buildFromSQL('Production_issue',
				"SELECT production_issues.* FROM production_issues, (SELECT * FROM users WHERE branch_id = '$branch') AS tbl WHERE production_issues.issuer = tbl.username AND production_issues.doc_type = '$doctype' AND YEAR( production_issues.doc_date ) = YEAR( CURDATE( ) ) AND MONTH( production_issues.doc_date ) = MONTH( CURDATE( ))",
				"SELECT count(*) FROM production_issues"
			);
	}
 }

?>