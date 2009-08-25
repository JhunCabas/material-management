<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "edit")
		{
			try {
				$inv_subcategory = new Inv_subcategory($_POST['key']);
				$inv_subcategory->populate();
				$inv_subcategory->store();
		 
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$inv_subcategory = new Inv_subcategory($_POST['key']);
				$inv_subcategory->delete();
				
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "add")
		{
			try{
				$inv_subcategory = new Inv_subcategory();
				$inv_subcategory->populate();
				$inv_subcategory->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "option")
		{
			Inv_subcategory::findOptionByMainCategoryCode($_POST['key']);
		}
	}
?>