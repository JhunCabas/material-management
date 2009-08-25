<?php
	include '../../resources/init.php';
	$q = strtolower($_GET["q"]);
	if (!$q) return;

	$resultArray = Inv_item::findBySubCategoryCode('AT');
	//print_r($resultArray);

	$items = array();
/*
	foreach ($resultArray as $i => $value)
	{
		$items[$resultArray[$i]['model']] = $resultArray[$i]['name'];
	}
	echo "[";
	//print_r($items);
	foreach ($items as $key=>$value) {
		if (strpos(strtolower($key), $q) !== false) {
			echo "{ name: \"$key\", to: \"$value\" }, ";
		}
	}
	echo "]";
*/
?>