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
				$json_form = fRequest::get('jsonForm');
				$jsonForm = fJSON::decode($json_form);
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
		}else if($_POST['type'] == "removeStock")
		{
			$records = Inv_stock::gotoStock($_POST['itemCode'],$_POST['branch']);
			if($records->count())
				foreach($records as $record)
				{
					if($record->getQuantity() < $_POST['itemQuan'])
						echo "Item not enough quantity";
					else
					{
						Inv_stock::removeStock($_POST['itemCode'],$_POST['branch'],$_POST['itemQuan']);
						$stock = new Production_issue_detail($_POST['detailId']);
						$stock->setStatus('completed');
						$stock->store();
					}
				}
			else
				echo "Item not enough quantity.";
		}else if($_POST['type'] == "save")
		{
			try{
				$production = new Production_issue($_POST['key']);
				//$production->populate();
				if($_POST['issuer'] != "")
				{
					$production->setIssuer($_POST['issuer']);
					$production->setIssuerDate($_POST['issuer_date']);
				}
				if($_POST['receiver'] != "")
				{
					$production->setReceiver($_POST['receiver']);
					$production->setReceiverDate($_POST['receiver_date']);
				}
				$production->store();
			}catch (fExpectedException $e) {
					echo $e->printMessage();
			}
		}else if($_POST['type'] == "cancel")
		{
			try{
				$production = new Production_issue($_POST['key']);
				$production->setStatus("cancelled");
				$production->store();
			}catch (fExpectedException $e) {
					echo $e->printMessage();
			}
		}
		else if($_POST['type'] == "count")
		{
			//$records = Production_issue::findAll();
			$records = Production_issue::findByBranch(fRequest::get('branch','string'),fRequest::get('doctype','string'));
			echo sprintf("%03d",$records->count() + 1);
		}else if($_POST['type'] == "deleteDetail")
		{
			Production_issue_detail::deleteDetail($_POST['key']);
		}
	}
?>