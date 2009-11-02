<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "edit")
		{
			try {
				$inv_classification = new Inv_classification($_POST['key']);
				$inv_classification->populate();
				$inv_classification->store();
		 
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$inv_classification = new Inv_classification($_POST['key']);
				$inv_classification->delete();
				
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "add")
		{
			try{
				$inv_classification = new Inv_classification();
				$inv_classification->populate();
				$inv_classification->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "option")
		{
			Inv_classification::findOptionBySubCategoryCode($_POST['key']);
		}
	}
?>