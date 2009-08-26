<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$purchase = new Purchase();
				$purchase->populate();
				$jsonForm = fJSON::decode($_POST['jsonForm']);
				foreach($jsonForm as $row)
				{
					try{
						$purchase_detail = new Purchase_detail();
						$purchase_detail->setDocNumber($_POST['doc_number']);
						$purchase_detail->setItemId($row->{'itemCode'});
						$purchase_detail->setQuantity($row->{'itemQuan'});
						$purchase_detail->setUnitPrice($row->{'itemUnitP'});
						$purchase_detail->setExtendedPrice($row->{'itemExtP'});
						$purchase_detail->store();
					}catch (fExpectedException $e) {
						echo $e->printMessage();
					}
					
				}
				$purchase->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}
?>