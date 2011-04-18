<?php
include './resources/init.php';

//Find all items
$results = Inv_item::findIdUB($db);
//$results = Inv_item::findAll();
foreach ($results as $result) {

	echo $result . " \n";
}

?>