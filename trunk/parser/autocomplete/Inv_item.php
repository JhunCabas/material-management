<?php
include '../../resources/init.php';
$q = strtolower($_GET["q"]);
if (!$q) return;
$items = Inv_item::findLike($q);
//echo $items->count();
$jsonString = "[";
foreach($items as $item)
{
	if(strpos(strtolower($item->getId()),$q) !== false){
		$jsonString = $jsonString . "{ name: \"".$item->prepareId()."\", desc: \"".$item->prepareDescription()."\", uom:\"".$item->prepareUnitOfMeasure()."\" },";
	}
}
$jsonString = substr($jsonString,0,strlen($jsonString)-1) . "]";
echo $jsonString;
?>