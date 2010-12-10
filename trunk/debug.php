<?php
include './resources/init.php';

//Find all available branches
$branches = Branch::findAll();

//Find all items
$items = Inv_item::findAll();
echo "Counting Different Items> ";
$number = $items->count();
echo $number."<br />";

$times =  ceil($items->count()/500);

for($i = 0; $i < 2; $i++)
{
	$start = $i*500;
	$limitedItems = Inv_item::findAllLimit($start,500);
	$section = $i+1;
	echo $start." Section No. ".$section."<br />";
	echo "Found ".$limitedItems->count()."<br />";
	
	foreach($limitedItems as $limitedItem)
	{
		// One branch
		$branch = $branches[0];
		//printf("%s <br />",$limitedItem->prepareId());
		//foreach($branches as $branch)
		//{
			try{
				$stockFinder = Inv_stock::findStockByBranch($limitedItem->getId(),$branch->getId());
				$stockFinder ->tossIfEmpty($branch->getId().' do not have '.$limitedItem->getId());
			} catch (fEmptySetException $e) {
				$e->printMessage();
				echo "<p>Creating stock entry for ".$limitedItem->getId()." at ".$branch->getId();
				$newStock = new Inv_stock();
				$newStock->setBranchId($branch->getId());
				$newStock->setItemId($limitedItem->getId());
				$newStock->store();
				echo "[Complete]</p>";
			}
		//}
	}
}

?>