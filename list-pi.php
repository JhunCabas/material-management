<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" charset="utf-8">
	$(function (){
		$("tr.linkable").click(function (){
			window.location = "document-pi-view.php?"+"id="+$(this).children(".docNumber").text();
		});
	});
</script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Production Issue</h2>
	<h3>Add Production Issue : <a href="document-pi.php">Form</a></h3>
	<?php 
		if(fAuthorization::checkAuthLevel('admin'))
		{
	?>
	<h3>List (Pending)</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>Issuer and Receiver</th><th style="width: 100px;">Status</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$productionEntries = Production_issue::findByStatus('pending');
					foreach($productionEntries as $productionEntry)
					{
						
						//$statusAll = "completed";
						//if($productionEntry->getStatus() == "pending")
						//{
							$statusAll = "checking";
							$productionDetails = Production_issue_detail::findDetail($productionEntry->getDocNumber());
							foreach($productionDetails as $productionDetail)
							{
								if($productionDetail->getStatus() == "pending")
									$statusAll = "pending";
							}
							if($statusAll == "checking")
							{
								$statusAll = "completed";
								$productionEntry->setStatus("completed");
								$productionEntry->store();
							}
						//}	
						//else
						//{
						//	$statusAll = $productionEntry->prepareStatus();
						//}
						
						echo "<tr class=\"linkable\"><td class=\"docNumber\">".$productionEntry->prepareDocNumber()."</td>";
						echo "<td>".$productionEntry->prepareDocDate("j F Y")."</td>";
						echo "<td>".$productionEntry->prepareIssuer()."</td>";
						echo "<td>".$statusAll."</td></tr>";
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
	<?php }?>
	<h3>List (Completed)</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>Issuer and Receiver</th><th style="width: 100px;">Status</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$productionEntries = Production_issue::findByStatus('completed',10);
					foreach($productionEntries as $productionEntry)
					{
						echo "<tr class=\"linkable\"><td class=\"docNumber\">".$productionEntry->prepareDocNumber()."</td>";
						echo "<td>".$productionEntry->prepareDocDate("j F Y")."</td>";
						echo "<td>".$productionEntry->prepareIssuer()."</td>";
						echo "<td>".$productionEntry->prepareStatus()."</td></tr>";
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>