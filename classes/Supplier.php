<?php
/**
 * Supplier
 */
 
 class Supplier extends fActiveRecord
 {
	/**
     * Returns all entries
     * 
     * @return fRecordSet  An object containing all entries
     */
    static function findAll()
    {
        return fRecordSet::build('Supplier',
			array('status>='=>0),
			array('name'=>'asc')
		);    
    }
	
	static function findActive()
	{
		return fRecordSet::build('Supplier',array('status='=>1));
	}

	static function generateInfo($key)
	{
		$supplier = new self($key);
		echo $supplier->prepareLine1()."<br />";
		echo $supplier->prepareLine2()."<br />";
		echo $supplier->prepareLine3()."<br />";
		echo "<b>Contact Person:</b> ".$supplier->prepareContactPerson()."<br />";
		echo "<b>Phone:</b> ".$supplier->prepareContact()."<br />";
		echo "<b>Fax:</b> ".$supplier->prepareFaxNo()."<br />";
	}
 }

?>