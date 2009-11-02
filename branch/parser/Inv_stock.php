<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "transfer")
		{
			$json_form = fRequest::get('jsonForm');
			$jsonForm = fJSON::decode($json_form);
			foreach($jsonForm as $row)
			{
				try{
					Inv_stock::removeStock($row->{'itemCode'},$row->{'branch'},$row->{'quantity'});
					$mattrans = new Material_transfer($_POST['doc_num']);
					$mattrans->setStatus("completed");
					$mattrans->store();
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
				Inv_stock::addStock($row->{'itemCode'},$_POST['branch'],$row->{'quantity'});
			}
		}
	}
?>