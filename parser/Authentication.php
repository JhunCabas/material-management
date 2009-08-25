<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "changePassword")
		{
			$hash = sha1($_POST['password']);
			$user = new User($_POST['username']);
			$user->setPassword($hash);
		}else if($_POST['type'] == "resetPassword")
		{
			$hash = sha1("password");
			$user = new User($_POST['username']);
			$user->setPassword($hash);
		}
	}
?>