<?php
include '../../resources/init.php';
$q = strtolower($_GET["q"]);
if (!$q) return;
$pos = Purchase::findAllUncompletePO();
$jsonString = "[";
foreach($pos as $po)
{
	if(strpos(strtolower($po->getPoNumber()),$q) !== false){
		$jsonString = $jsonString . "{ name: \"".$po->preparePoNumber()."\", to: \"".$po->prepareDocNumber()."\" },";
	}
}
$jsonString = substr($jsonString,0,strlen($jsonString)-1) . "]";
echo $jsonString;
?>