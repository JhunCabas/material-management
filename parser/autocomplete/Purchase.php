<?php
include '../../resources/init.php';
$q = strtolower($_GET["q"]);
if (!$q) return;
$pos = Purchase::findAllUncompletePO();
$jsonString = "[";
foreach($pos as $po)
{
	if(strpos(strtolower($po->getDocNumber()),$q) !== false){
		$jsonString = $jsonString . "{ name: \"".$po->prepareDocNumber()."\", to: \"".$po->prepareStatus()."\" },";
	}
}
$jsonString = substr($jsonString,0,strlen($jsonString)-1) . "]";
echo $jsonString;
?>