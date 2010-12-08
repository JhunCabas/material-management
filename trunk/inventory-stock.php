<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/inventory-stock.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuInventory'); ?>
	<div id="main" class="span-24 last">
		<h2>Stock</h2>
		<div class="span-23 last">
					<a class="exporter" href="./report/export.php?op=stock&type=doc">Export to Word</a>
					<a class="exporter" href="./report/export.php?op=stock&type=xls">Export to Excel</a>
		</div>
		<br />
		<form action = "inventory-stock.php" method = "get">
			<label for="branch">Choose Branch: </label>
			<span id="branch">
				<select name = "branch">
					<?php Branch::findAllOption(); ?>
				</select>
			</span>
			<input type="hidden" name="page" value="1" />
			<input id="submitBTN" type="submit" value="Submit" />
		</form>
		<form action = "inventory-stock.php" method = "get">
			<label for="inv_item">Choose Inventory Item: </label>
			<span id="inv_item">
				<input id="autocompleteItem" name="item"></input>
			</span>
			<input id="submitBTN" type="submit" value="Submit" />
		</form>
		<?php 
		if(isSet($_GET['branch']))
		{
			if(!(isSet($_GET['page'])) || fRequest::get('page', 'integer') == 1)
			{
				$start = 1; 
				$end = 500;
			}else
			{
				$start = (fRequest::get('page', 'integer') - 1)*500 + 1;
				$end = $start + 500 - 1;
			}
			$branch	= fRequest::get('branch', 'string');
			$itemId = fRequest::get('item', 'string');
			try{
				$inv_stocks = Inv_stock::findByBranchLimit($branch,$start,$end);
				$times =  ceil($inv_stocks->count(TRUE)/500);
				$branchName = new Branch($_GET['branch']);
				echo "<h3>".$branchName->prepareName()."</h3>";
				if($times < 2)
				{
					echo "<span id=\"pagination\"><a href=\"inventory-stock.php?branch=$branch\">First </a>";
					echo "<a href=\"inventory-stock.php?branch=$branch&page=$times\">Last</a></span>";
				}else
				{
					echo "<span id=\"pagination\"><a href=\"inventory-stock.php?branch=$branch\">First </a>";
					for($i=1;$i<$times;$i++)
					{
						echo "<a href=\"inventory-stock.php?branch=$branch&page=$i\">$i </a></span>";
					}
					echo "<a href=\"inventory-stock.php?branch=$branch&page=$times\">Last</a></span>";
				}
				
				
				printf("<table><thead><tr><th>Item ID</th><th>Description</th><th>Quantity</th></tr></thead><tbody>");
				foreach($inv_stocks as $inv_stock)
				{
					$inv_item = new Inv_item($inv_stock->getItemId());
					printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>"
						,$inv_item->prepareId()
						,$inv_item->prepareDescription()
						,$inv_stock->prepareQuantity()." ".$inv_item->prepareUnitOfMeasure());
				}
				echo "</tbody></table>";
			} catch (fExpectedException $e) {
				echo $e->printMessage().$inv_stock->getItemId();
			}
		}else if(isSet($_GET['item']))
		{
			try{
				$inv_stocks = Inv_stock::findByItem($_GET['item']);
				$item = new Inv_item($_GET['item']);
				printf("<br /><h3>%s - %s</h3><table><thead><tr><th>Branch</th><th>Quantity</th></tr></thead><tbody>"
					,$item->prepareId(),$item->prepareDescription());
				foreach($inv_stocks as $inv_stock)
				{
					$branch = new Branch($inv_stock->getBranchId());
						printf("<tr><td>%s</td><td>%s</td></tr>",$branch->prepareName()." [".$branch->prepareId()."]",$inv_stock->prepareQuantity()." ".$item->prepareUnitOfMeasure());
				}
				echo "</tbody></table>";
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>