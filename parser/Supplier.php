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
		}else if($_POST['type'] == "add")
		{
			try {
				$supplier = new Supplier();
				$supplier->populate();
				$supplier->store();
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "option")
		{
			echo "<select>";
			Status::printOption('1');
			echo "</select>";
		}
	}
?>