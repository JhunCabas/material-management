<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$doctype = new Document_type();
				$doctype->populate();
				$doctype->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}
?>