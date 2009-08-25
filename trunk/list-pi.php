<?php
include './resources/init.php';
$tmpl->place('header');
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Purchase Request</h2>
	<h3>Add Purchase Request : <a href="document-pi.php">Form</a></h3>
	<h3>List</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>Issuer</th><th>Receiver</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$productionEntries = Production_issue::findAll();
					foreach($productionEntries as $productionEntry)
					{
						printf("<tr><td>%s</td>%s<td>%s</td><td>%s</td></tr>"
							,$productionEntry->prepareDocNumber()
							,$productionEntry->prepareDocDate()
							,$productionEntry->prepareIssuer()
							,$productionEntry->prepareReceiver());
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>