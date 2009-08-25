<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "edit")
		{
			try {
				$inv_maincategory = new Inv_maincategory($_POST['key']);
				$inv_maincategory->populate();
				$inv_maincategory->store();
				
				$inv_subcategories = Inv_subcategory::findByMainCategoryCode($_POST['key']);
				foreach($inv_subcategories as $inv_subcategory)
				{
					$inv_subcategory->setMainCategoryCode($_POST['category_code']);
					$inv_subcategory->store();
				}
					
		 
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$inv_maincategory = new Inv_maincategory($_POST['key']);
				$inv_maincategory->delete();
				
				$inv_subcategories = Inv_subcategory::findByMainCategoryCode($_POST['key']);
				foreach($inv_subcategories as $inv_subcategory)
					$inv_subcategory->delete();
				
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "add")
		{
			try{
				$inv_maincategory = new Inv_maincategory();
				$inv_maincategory->populate();
				$inv_maincategory->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}
?>