<?php
include './resources/init.php';
$tmpl->place('header');
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Purchase Order</h2>
	<h3>List</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>Requester</th><th>Status</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$purchaseEntries = Purchase::findAllPO();
					foreach($purchaseEntries as $purchaseEntry)
					{
						printf("<tr><td>%s</td>%s<td>%s</td><td>%s</td></tr>"
							,$purchaseEntry->prepareDocNumber()
							,$purchaseEntry->prepareDocDate()
							,$purchaseEntry->prepareRequester()
							,$purchaseEntry->prepareStatus());
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>