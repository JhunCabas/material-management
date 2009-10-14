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
		}else if($_POST['type'] == "generate")
		{
			try{
				$supplier = new Supplier($_POST['key']);
				echo $supplier->prepareLine1()."<br />";
				echo $supplier->prepareLine2()."<br />";
				echo $supplier->prepareLine3()."<br />";
				echo "<b>Contact Person:</b> ".$supplier->prepareContactPerson()."<br />";
				echo "<b>Phone:</b> ".$supplier->prepareContact()."<br />";
				echo "<b>Fax:</b> ".$supplier->prepareFaxNo()."<br />";
			} catch (fExpectedException $e) {
				echo "Invalid supplier";
			}
		}
	}
?>