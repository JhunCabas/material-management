<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "add")
		{
			try{
				$inv_item = new Inv_item();
				$inv_item->populate();
				$inv_item->store();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "delete")
		{
			try {
				$inv_item = new Inv_item($_POST['key']);
				$inv_item->delete();
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "edit")
		{
			try {
				$inv_item = new Inv_item($_POST['key']);
				$inv_item->populate();
				$inv_item->store();

			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "url")
		{
			try {
				$inv_item = new Inv_item($_POST['key']);
				$inv_item->setImageUrl($_POST['url']);
				$inv_item->store();

			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}else if($_POST['type'] == "option")
		{
			$selectedOption = $_POST['input'] == "Active" ? 1 : 0;
			echo "<select>";
			Status::printOption($selectedOption);
			echo "</select>";
		}else if($_POST['type'] == "lastCode")
		{
			$counter = Inv_item::findByClassificationCode($_POST['classific']);
			echo sprintf("%03d",$counter->count() + 1);
		}else if($_POST['type'] == "upload")
		{
			try{
				$uploadDirectory = new fDirectory('../storage/image/'.$_POST['hiddenId']);
			} catch (fExpectedException $e)
			{
				$uploadDirectory = fDirectory::create('../storage/image/'.$_POST['hiddenId']);
			}
			try{
				
				$uploader = new fUpload();
				$uploader->setMIMETypes(
				    array(
					        'image/gif',
					        'image/jpeg',
					        'image/pjpeg',
					        'image/png'
					),
					    'The file uploaded is not an image'
				);
				$uploader->enableOverwrite();
				$file = $uploader->move($uploadDirectory, 'file');
				$inv_item = new Inv_item($_POST['hiddenId']);
				$inv_item->setImageUrl('storage/image/'.$_POST['hiddenId'].'/'.$file->getFilename());
				$inv_item->store();
				echo "Image uploaded";
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}else if(isSet($_GET['type']))
	{
		if($_GET['type'] == "uom")
		{
			try{
				$inv_item = new Inv_item($_GET['key']);
				echo $inv_item->prepareUnitOfMeasure();
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	}
?>