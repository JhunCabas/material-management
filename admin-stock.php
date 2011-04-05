<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/admin-stock.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuAdmin'); ?>
	<div id="main" class="span-24 last">
		<h2>Administration</h2><h3>Stock</h3>
		<br />
		<form action = "admin-stock.php" method = "get">
			<label for="inv_item">Choose Inventory Item: </label>
			<span id="inv_item">
				<input id="autocompleteItem" name="item"></input>
			</span>
			<input id="submitBTN" type="submit" value="Submit" />
		</form>
		<?php 
		if(isSet($_GET['item']))
		{
			try{
				$inv_stocks = Inv_stock::findByItem($_GET['item']);
				$item = new Inv_item($_GET['item']);
				printf("<br /><h3>%s - %s</h3><table><thead><tr><th>Branch</th><th>Quantity</th><th width=\"100\">Update</th></tr></thead><tbody>"
					,$item->prepareId(),$item->prepareDescription());
				foreach($inv_stocks as $inv_stock)
				{
					$branch = new Branch($inv_stock->getBranchId());
						printf("<tr><td>%s</td><td><input type=\"text\" value=\"%s\" /> %s</td><td><input type=\"button\" value=\"Update\"/></td></tr>",$branch->prepareName()." [".$branch->prepareId()."]",$inv_stock->prepareQuantity(),$item->prepareUnitOfMeasure());
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