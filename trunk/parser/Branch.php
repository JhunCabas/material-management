<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "option")
		{
			Branch::findAllOption();
		}else if($_POST['type'] == "edit")
		{
			try {
				$branch = new Branch($_POST['key']);
				$branch->populate();
				$branch->store();
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$branch = new Branch($_POST['key']);
				$branch->delete();

			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "add")
		{
			try{
				$branch = new Branch();
				$branch->populate();
				$branch->store();
								
				$inv_items = Inv_item::findAll();
				foreach($inv_items as $inv_item)
				{
					$stock = new Inv_stock();
					$stock->setBranchId($branch->getId());
					$stock->setItemId($inv_item->getId());
					$stock->setQuantity(0);
					$stock->store();
				}
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "name")
		{
			$branch = new Branch($_POST['key']);
			echo $branch->prepareName();
		}
	}
?>