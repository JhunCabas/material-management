<?php
	include '../resources/init.php';
	
	if(isSet($_POST['type']))
	{
		if($_POST['type'] == "upload")
		{
			$uploadDirectory = new fDirectory('storage/image'.$_POST['hiddenId']);
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
			$file = $uploader->move($uploadDirectory, 'file_input_name');
		}
	}
?>