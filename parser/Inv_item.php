<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$inv_item = new Inv_item();
				$inv_item->populate();
				$inv_item->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$inv_item = new Inv_item($_POST['key']);
				$inv_item->delete();
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "edit")
		{
			try {
				$inv_item = new Inv_item($_POST['key']);
				$inv_item->populate();
				$inv_item->store();

			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "url")
		{
			try {
				$inv_item = new Inv_item($_POST['key']);
				$inv_item->setImageUrl($_POST['url']);
				$inv_item->store();

			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "option")
		{
			echo "<select>";
			Status::printOption();
			echo "</select>";
		}
	}
?>