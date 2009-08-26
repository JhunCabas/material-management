<?php
	include '../resources/init.php';
	$error = false;
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$production = new Production_issue();
				$production->populate();
				$jsonForm = fJSON::decode($_POST['jsonForm']);
				if(!$error)
					$production->store();
				foreach($jsonForm as $row)
				{
					try{
						$production_detail = new Production_issue_detail();
						$production_detail->setDocNumber($_POST['doc_number']);
						$production_detail->setItemId($row->{'itemCode'});
						$production_detail->setQuantity($row->{'itemQuan'});
						$production_detail->setRemark($row->{'remarks'});
						if(!$error)
							$production_detail->store();
					}catch (fExpectedException $e) {
						echo $e->printMessage();
						$error = true;
					}
					
				}
			}catch (fExpectedException $e) {
				echo $e->printMessage();
				$error = true;
			}
		}
	}
?>