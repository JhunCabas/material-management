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
		}else if($_POST['type'] == "edit")
		{
			try{
				$purchase = new Purchase($_POST["key"]);
				$purchase->populate();
				$jsonForm = fJSON::decode($_POST['jsonForm']);
				if(($_POST['approver_1'] != null)&&($_POST['approver_1_date'] != null))
				{
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
						$purchase_detail->setDocNumber($_POST["key"]);
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