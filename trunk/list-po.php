<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" charset="utf-8">
	$(function (){
		$("tr.linkable").click(function (){
			window.location = "document-po-view.php?"+"id="+$(this).children(".docNumber").text();
		});
	});
</script>
<?php
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
						echo "<tr class=\"linkable\"><td class=\"docNumber\">".$purchaseEntry->prepareDocNumber()."</td>";
						echo "<td>".$purchaseEntry->prepareDocDate("j F Y")."</td>";
						echo "<td>".$purchaseEntry->prepareRequester()."</td>";
						echo "<td>".$purchaseEntry->prepareStatus()."</td></tr>";
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>