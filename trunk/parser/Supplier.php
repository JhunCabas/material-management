<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "edit")
		{
			try {
				$supplier = new Supplier($_POST['key']);
				$supplier->populate();
				$supplier->store();
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}
?>