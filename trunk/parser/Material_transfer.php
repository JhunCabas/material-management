<?php
	include '../resources/init.php';
	$error = false;
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$mattrans = new Material_transfer();
				$mattrans->populate();
				$jsonForm = fJSON::decode($_POST['jsonForm']);
				if(!$error)
					$mattrans->store();
				foreach($jsonForm as $row)
				{
					try{
						$mattrans_detail = new Material_transfer_detail();
						$mattrans_detail->setDocNumber($_POST['doc_number']);
						$mattrans_detail->setItemId($row->{'itemCode'});
						$mattrans_detail->setQuantity($row->{'itemQuan'});
						$mattrans_detail->setRemark($row->{'remark'});
						if(!$error)
							$mattrans_detail->store();
					}catch (fExpectedException $e) {
						echo $e->printMessage();
						$error = true;
					}
					
				}
			}catch (fExpectedException $e) {
				echo $e->printMessage();
				$error = true;
			}
		}else if($_POST['type'] == "count")
		{
			$records = Material_transfer::findAll();
			echo sprintf("%04d",$records->count() + 1);
		}
	}
?>