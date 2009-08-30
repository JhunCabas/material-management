<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "transfer")
		{
			$jsonForm = fJSON::decode($_POST['jsonForm']);
			foreach($jsonForm as $row)
			{
				try{
					Inv_stock::removeStock($row->{'itemCode'},$row->{'branch'},$row->{'quantity'});
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
				Inv_stock::addStock($row->{'itemCode'},$_POST['branch'],$row->{'quantity'});
			}
		}
	}
?>