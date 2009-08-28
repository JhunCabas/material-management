<?php
	include '../resources/init.php';
	$error = false;
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$purchase = new Purchase();
				$purchase->populate();
				$jsonForm = fJSON::decode($_POST['jsonForm']);
				if(!$error)
					$purchase->store();
				foreach($jsonForm as $row)
				{
					try{
						$purchase_detail = new Purchase_detail();
						$purchase_detail->setDocNumber($_POST['doc_number']);
						$purchase_detail->setItemId($row->{'itemCode'});
						$purchase_detail->setQuantity($row->{'itemQuan'});
						$purchase_detail->setUnitPrice($row->{'itemUnitP'});
						$purchase_detail->setExtendedPrice($row->{'itemExtP'});
						if(!$error)
							$purchase_detail->store();
					}catch (fExpectedException $e) {
						echo $e->printMessage();
						$error = true;
					}
					
				}
			}catch (fExpectedException $e) {
				echo $e->printMessage();
				$error = true;
			}
		}else if($_POST['type'] == "countPR")
		{
			$countPR = Purchase::findByDocType($_POST['doc']);
			echo sprintf("%04d",$countPR->count() + 1);
		}
	}
?>