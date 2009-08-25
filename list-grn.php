<?php
include './resources/init.php';
$tmpl->place('header');
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Good Receipt Note</h2>
	<h3>Add Good Receipt Note : <a href="document-grn.php">Form</a></h3>
	<h3>List</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>DO Number</th><th>PO Number</th><th>Status</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$grnEntries = Good_receipt_note::findAll();
					foreach($grnEntries as $grnEntry)
					{
						printf("<tr><td>%s</td>%s<td>%s</td><td>%s</td><td>%s</td></tr>"
							,$grnEntry->prepareDocNumber()
							,$grnEntry->prepareDocDate()
							,$grnEntry->prepareDoNo()
							,$grnEntry->preparePoNo()
							,$grnEntry->prepareStatus());
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>