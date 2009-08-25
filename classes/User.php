<?php
/**
 * User
 */
 
 class User extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('User');    
    }

 }

?>