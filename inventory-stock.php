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
			try{
				$inv_stocks = Inv_stock::findByBranch($_GET['branch']);
				$branch = new Branch($_GET['branch']);
				printf("<h3>%s</h3><table><thead><tr><th>Item ID</th><th>Description</th><th>Quantity</th></tr></thead><tbody>"
					,$branch->prepareName());
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
				echo $e->printMessage();
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