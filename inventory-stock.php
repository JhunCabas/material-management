<?php
include './resources/init.php';
$tmpl->place('header');
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuInventory'); ?>
	<div id="main" class="span-24 last">
		<h2>Stock</h2>
		<form action = "inventory-stock.php" method = "get">
			<label for="branch">Choose Main Category: </label>
			<span id="branch">
				<select name = "branch">
					<?php Branch::findAllOption(); ?>
				</select>
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
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>