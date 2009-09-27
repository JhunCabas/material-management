<?php
	include '../resources/init.php';
	$error = false;
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$grn = new Good_receipt_note();
				$grn->populate();
				$json_form = fRequest::get('jsonForm');
				$jsonForm = fJSON::decode($json_form);
				if(!$error)
					$grn->store();
				foreach($jsonForm as $row)
				{
					try{
						$grn_detail = new Good_receipt_note_detail();
						$grn_detail->setDocNumber($_POST['doc_number']);
						$grn_detail->setItemId($row->{'itemCode'});
						$grn_detail->setQuantity($row->{'itemQuan'});
						$grn_detail->setAssessment($row->{'assess'});
						$grn_detail->setRemark($row->{'remarks'});
						if(!$error)
							$grn_detail->store();
					}catch (fExpectedException $e) {
						echo $e->printMessage();
						$error = true;
					}
					
				}
				if($grn->getInspector() != null && $grn->getReceiver() != null)
				{
					$details = Good_receipt_note_detail::findDetail($grn->getDocNumber());
					foreach($details as $detail)
					{
						Inv_stock::addStock($detail->getItemId(),$grn->getBranchId(),$detail->getQuantity());
					}
					$grn->setStatus("completed");
					$grn->store();
				}
				if($_POST['cloneNew'] == "yes")
				{
					$newGRN = $grn->replicate();
					$newGRN->setDocNumber($grn->getDocNumber()."/rev");
					$newGRN->setInspector(null);
					$newGRN->setInspectorDate(null);
					$newGRN->setReceiver(null);
					$newGRN->setReceiverDate(null);
					$newGRN->store();
				}
			}catch (fExpectedException $e) {
				echo $e->printMessage();
				$error = true;
			}
		}else if($_POST['type'] == "count")
		{
			$records = Good_receipt_note::findAll();
			echo sprintf("%04d",$records->count() + 1);
		}else if($_POST['type'] == "save")
		{
			try{
				$grn = new Good_receipt_note($_POST['doc_number']);
				$grn->populate();
				if(!$error)
					$grn->store();
				if($_POST['jsonForm'] != "")
				{
					$json_form = fRequest::get('jsonForm');
					$jsonForm = fJSON::decode($json_form);
					foreach($jsonForm as $row)
					{
						try{
							$grn_detail = new Good_receipt_note_detail();
							$grn_detail->setDocNumber($_POST['doc_number']);
							$grn_detail->setItemId($row->{'itemCode'});
							$grn_detail->setQuantity($row->{'itemQuan'});
							$grn_detail->setAssessment($row->{'assess'});
							$grn_detail->setRemark($row->{'remarks'});
							if(!$error)
								$grn_detail->store();
						}catch (fExpectedException $e) {
							echo $e->printMessage();
							$error = true;
						}
					
					}
				}
				if($grn->getInspector() != null && $grn->getReceiver() != null)
				{
					$details = Good_receipt_note_detail::findDetail($grn->getDocNumber());
					foreach($details as $detail)
					{
						Inv_stock::addStock($detail->getItemId(),$grn->getBranchId(),$detail->getQuantity());
					}
					$grn->setStatus("completed");
					$grn->store();
				}
				if($_POST['cloneNew'] == "yes")
				{
					$newGRN = $grn->replicate();
					$newGRN->setDocNumber($grn->getDocNumber()."/rev");
					$newGRN->setInspector(null);
					$newGRN->setInspectorDate(null);
					$newGRN->setReceiver(null);
					$newGRN->setReceiverDate(null);
					$newGRN->store();
				}
			}catch (fExpectedException $e) {
				echo $e->printMessage();
				$error = true;
			}
		}
	}
?>