<?php
include '../../resources/init.php';
$q = strtolower($_GET["q"]);
if (!$q) return;
$suppliers = Supplier::findAll();
$jsonString = "[";
foreach($suppliers as $supplier)
{
	if(strpos(strtolower($supplier->getName()),$q) !== false){
		$jsonString = $jsonString . "{ name: \"".$supplier->prepareName().
									"\", to: \"".$supplier->prepareId().
									"\" },";
	}
}
$jsonString = substr($jsonString,0,strlen($jsonString)-1) . "]";
echo $jsonString;
?>