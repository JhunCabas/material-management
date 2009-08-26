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
				$jsonForm = fJSON::decode($_POST['jsonForm']);
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
			}catch (fExpectedException $e) {
				echo $e->printMessage();
				$error = true;
			}
		}
	}
?>