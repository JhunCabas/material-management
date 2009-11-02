<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$currency = new Currency();
				$currency->populate();
				$currency->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "edit")
		{
			try{
				$currency = new Currency($_POST['key']);
				$currency->populate();
				$currency->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "option")
		{
			Currency::findCurrentMonthOption($_POST['month'],$_POST['year']);
		}
	}
?>