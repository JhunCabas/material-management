<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "edit")
		{
			try {
				$user = new User($_POST['key']);
				$user->populate();
				$user->store();
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$user = new User($_POST['key']);
				$user->delete();

			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "add")
		{
			try{
				$user = new User();
				$user->populate();
				$user->setPassword(sha1(password));
				$user->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}
?>