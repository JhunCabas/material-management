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
				$json_form = fRequest::get('jsonForm');
				$jsonForm = fJSON::decode($json_form);
				if(!$error)
					$purchase->store();
				foreach($jsonForm as $row)
				{
					try{
						$purchase_detail = new Purchase_detail();
						$purchase_detail->setDocNumber($_POST['doc_number']);
						$purchase_detail->setItemId($row->{'itemCode'});
						$purchase_detail->setDescription($row->{'itemDesc'});
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
		}else if($_POST['type'] == "approve")
		{
			try{
				$key = "";
				$purchase = new Purchase();
				$purchase->populate();
				$json_form = fRequest::get('jsonForm');
				$jsonForm = fJSON::decode($json_form);
				$currentDocType = $_POST['doc_type'];
				switch($currentDocType)
				{
					case "PR1":
						$purchase->setDocType('PO1');
						$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO1', 0, 3);
						$key = $newDocNumber;
						$purchase->setDocNumber($newDocNumber);
						break;
					case "PR2";
						$purchase->setDocType('PO2');
						$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO2', 0, 3);
						$key = $newDocNumber;
						$purchase->setDocNumber($newDocNumber);
						break;
					case "PR3";
						$purchase->setDocType('PO3');
						$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO3', 0, 3);
						$key = $newDocNumber;
						$purchase->setDocNumber($newDocNumber);
						break;
					case "PR4";
						$purchase->setDocType('PO4');
						$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO4', 0, 3);
						$key = $newDocNumber;
						$purchase->setDocNumber($newDocNumber);
						break;
				}
				$purchase->setStatus('approved');
				$purchase->setDocTag('po');
				$purchase->setDocNumber($key);
				if(!$error)
					$purchase->store();
				foreach($jsonForm as $row)
				{
					try{
						$purchase_detail = new Purchase_detail();
						$purchase_detail->setDocNumber($key);
						$purchase_detail->setItemId($row->{'itemCode'});
						$purchase_detail->setDescription($row->{'itemDesc'});
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
		}else if($_POST['type'] == "edit")
		{
			try{
				$key = $_POST["key"];
				$purchase = new Purchase($key);
				$purchase->populate();
				$json_form = fRequest::get('jsonForm');
				$jsonForm = fJSON::decode($json_form);
				if(($_POST['approver_1'] != null)&&($_POST['approver_1_date'] != null))
				{
					$currentDocType = $purchase->getDocType();
					switch($currentDocType)
					{
						case "PR1":
							$purchase->setDocType('PO1');
							$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO1', 0, 3);
							$key = $newDocNumber;
							$purchase->setDocNumber($newDocNumber);
							break;
						case "PR2";
							$purchase->setDocType('PO2');
							$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO2', 0, 3);
							$key = $newDocNumber;
							$purchase->setDocNumber($newDocNumber);
							break;
						case "PR3";
							$purchase->setDocType('PO3');
							$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO3', 0, 3);
							$key = $newDocNumber;
							$purchase->setDocNumber($newDocNumber);
							break;
						case "PR4";
							$purchase->setDocType('PO4');
							$newDocNumber = substr_replace($purchase->getDocNumber(), 'PO4', 0, 3);
							$key = $newDocNumber;
							$purchase->setDocNumber($newDocNumber);
							break;
					}
					$purchase->setStatus('approved');
					$purchase->setDocTag('po');
				}
				if(!$error)
					$purchase->store();
				foreach($jsonForm as $row)
				{
					try{
						if($row->{'detailId'}==0)
							$purchase_detail = new Purchase_detail();
						else
							$purchase_detail = new Purchase_detail($row->{'detailId'});
						$purchase_detail->setDocNumber($key);
						$purchase_detail->setItemId($row->{'itemCode'});
						$purchase_detail->setDescription($row->{'itemDesc'});
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
			$countPR = Purchase::findAll();
			echo sprintf("%04d",$countPR->count() + 1);
		}else if($_POST['type'] == "json")
		{
			$purchasedetails = Purchase_detail::findDetail($_POST['key']);
			echo $purchasedetails->toJSON();
		}
	}
?>