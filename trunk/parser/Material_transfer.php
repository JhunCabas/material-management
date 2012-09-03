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
				$json_form = fRequest::get('jsonForm');
				$jsonForm = fJSON::decode($json_form);
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
		}else if($_POST['type'] == "cancel")
		{
			try{
				$mattrans = new Material_transfer($_POST['doc_num']);
				$mat_details = Material_transfer_detail::findDetail($mattrans->getDocNumber());
				foreach($mat_details as $mat_detail)
				{
					Inv_stock::rejectTransit($mat_detail->getItemId(),$mattrans->getBranchFrom(),$mat_detail->getQuantity());
				}
				$mattrans->setStatus("cancelled");
				$mattrans->store();
			}catch (fExpectedException $e)
			{
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "count")
		{
			$records = Material_transfer::findCurrentMonth($_POST['branch']);
			echo sprintf("%03d",$records->count() + 1);
		}else if($_POST['type'] == "transit")
		{
			try{
				$mattrans_detail = new Material_transfer_detail($_POST['key']);
				$mattrans = new Material_transfer($mattrans_detail->getDocNumber());
				if($mattrans_detail->getStatus() != "pending")
					throw new fProgrammerException('Status Overidden: '.$mattrans_detail->getStatus());
				Inv_stock::transitStock($mattrans_detail->getItemId(),$mattrans->getBranchFrom(),$mattrans_detail->getQuantity());
				$mattrans_detail->setStatus("transit");
				$mattrans_detail->setApprover($_POST['user']);
				$mattrans_detail->store();
				
				$mat_details = Material_transfer_detail::findDetail($mattrans->getDocNumber());
				foreach($mat_details as $mat_detail)
				{
					if($mat_detail->getStatus() == "completed")
						$mattrans->setStatus("completed");
					else
						$mattrans->setStatus("pending");
				}
				$mattrans->store();
			}catch (fExpectedException $e)
			{
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "reject")
		{
			try{
				$mattrans_detail = new Material_transfer_detail($_POST['key']);
				$mattrans = new Material_transfer($mattrans_detail->getDocNumber());
				if($mattrans_detail->getStatus() != "transit")
					throw new fProgrammerException('Status Overidden: '.$mattrans_detail->getStatus());
				Inv_stock::rejectTransit($mattrans_detail->getItemId(),$mattrans->getBranchFrom(),$mattrans_detail->getQuantity());
				$mattrans_detail->setStatus("pending");
				$mattrans_detail->setApprover(NULL);
				$mattrans_detail->store();
				
				$mat_details = Material_transfer_detail::findDetail($mattrans->getDocNumber());
				foreach($mat_details as $mat_detail)
				{
					if($mat_detail->getStatus() == "completed")
						$mattrans->setStatus("completed");
					else
						$mattrans->setStatus("pending");
				}
				$mattrans->store();
			}catch (fExpectedException $e)
			{
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "accept")
		{
			try{
				$mattrans_detail = new Material_transfer_detail($_POST['key']);
				$mattrans = new Material_transfer($mattrans_detail->getDocNumber());
				if($mattrans_detail->getStatus() != "transit")
					throw new fProgrammerException('Status Overidden: '.$mattrans_detail->getStatus());
				Inv_stock::moveTransit($mattrans_detail->getItemId(),
					$mattrans->getBranchFrom(),$mattrans->getBranchTo(),$mattrans_detail->getQuantity());
				$mattrans_detail->setStatus("completed");
				$mattrans_detail->setReceiver($_POST['user']);
				$mattrans_detail->store();
				
				$mat_details = Material_transfer_detail::findDetail($mattrans->getDocNumber());
				foreach($mat_details as $mat_detail)
				{
					if($mat_detail->getStatus() == "completed")
						$mattrans->setStatus("completed");
					else
						$mattrans->setStatus("pending");
				}
				$mattrans->store();
			}catch (fExpectedException $e)
			{
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "deleteDetail")
		{
			Material_transfer_detail::deleteDetail($_POST['key']);
		}
	}
?>