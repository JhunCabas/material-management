<?php
/**
 * Inventory Item
 */
 
 class Inv_item extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Inv_item');    
    }
	
	static function findAllUB($db)
	{
		return $db->unbufferedQuery("SELECT * FROM inv_items");
	}
	
	static function findId()
	{
		$results = fRecordSet::build('Inv_item');  
		$listofId = array();
		foreach($results as $result)
		{
			array_push($listofId,$result->getId());
		}
		
		return $listofId;
	}
	
	static function findIdUB($db)
	{
		$results = $db->unbufferedQuery("SELECT id FROM inv_items"); 
		$listofId = array();
		foreach($results as $result)
		{
			array_push($listofId,$result['id']);
		}
		
		return $listofId;
	}
	
	static function findLike($key)
	{
		return fRecordSet::build('Inv_item',
			array('id~' => $key));
	}

	static function findAllLimit($first = 0, $number = 500)
	{
		return fRecordSet::buildFromSQL('Inv_item',
			"SELECT * FROM inv_items ORDER BY `id` ASC LIMIT $first , $number",
			"SELECT count(*) FROM inv_items");
	}
	
	static function findByClassificationCode($key)
	{
		return fRecordSet::build('Inv_item',
			array('classification_code=' => $key));
	}
	
	static function findBySubCategoryCode($key)
	{
		return fRecordSet::build('Inv_item',
			array('sub_category_code=' => $key));
	}
	
	static function findByMainCategoryCode($key)
	{
		return fRecordSet::build('Inv_item',
			array('main_category_code=' => $key));
	}
	
	static function fastCountAll()
	{
		return $db->query('SELECT count(*) FROM inv_items');
	}
 }

?>