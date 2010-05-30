<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" charset="utf-8">
	$(function (){
		$("tr.linkable.PR").click(function (){
			window.location = "document-pr-view.php?"+"id="+$(this).children(".docNumber").text();
		});
		$("tr.linkable.PO").click(function (){
			window.location = "document-po-view.php?"+"id="+$(this).children(".docNumber").text();
		});
	});
</script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Purchase Request</h2>
	<h3>Add Purchase Request : <a href="document-pr.php">Form</a></h3>
	<h3>MOF : <a href="list-mof.php">Search</a></h3>
	<?php 
		if(fAuthorization::checkAuthLevel('admin'))
		{
	?>
	<h3>List</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>Requester</th><th>Status</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$purchaseEntries = Purchase::findAllPR();
					$numPurchases = $purchaseEntries->count();
					//echo "Number of Purchases queries: ".$numPurchases;
					foreach($purchaseEntries as $purchaseEntry)
					{
						echo "<tr class=\"linkable PR\"><td class=\"docNumber\">".$purchaseEntry->prepareDocNumber()."</td>";
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
	<h3>Approved List</h3>
	<table>
		<thead>
			<tr><th>Purchase Order No</th><th>Purchase Request No</th><th>Approved Date</th><th>Requester</th><th>Status</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$purchaseEntries = Purchase::findAllPO(20);
					foreach($purchaseEntries as $purchaseEntry)
					{
						//$newDocNumber = substr_replace($purchaseEntry->getDocNumber(), 'PR', 0, 2);
						echo "<tr class=\"linkable PO\"><td>".$purchaseEntry->preparePoNumber()."</td><td class=\"docNumber\">".$purchaseEntry->prepareDocNumber()."</td>";
						echo "<td>".$purchaseEntry->prepareApprover1Date("j F Y")."</td>";
						echo "<td>".$purchaseEntry->prepareRequester()."</td>";
						echo "<td>".$purchaseEntry->prepareStatus()."</td></tr>";
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
				
			?>
		</tbody>
	</table>
	<?php }?>
</div>
<?php $tmpl->place('footer'); ?>