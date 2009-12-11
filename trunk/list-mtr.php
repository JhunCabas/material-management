<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" charset="utf-8">
	$(function (){
		$("tr.linkable").click(function (){
			window.location = "document-mtr-view.php?"+"id="+$(this).children(".docNumber").text();
		});
	});
</script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Material Transfer</h2>
	<h3>Add Material Transfer Form : <a href="document-mtr.php">Form</a></h3>
	<?php 
		if(fAuthorization::checkAuthLevel('admin'))
		{
	?>
	<h3>List - Uncomplete</h3>
	<table>
		<thead>
			<tr>
				<th>Document Number</th><th>Document Date</th><th>Requester</th>
				<th>from Department</th><th>to Department</th><th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				try{
					$mattrans = Material_transfer::findAllUncomplete(20);
					foreach($mattrans as $mattran)
					{
						//$branch = new Branch($mattran->getBranchId());
						$toBranch = new Branch($mattran->getBranchTo());
						$fromBranch = new Branch($mattran->getBranchFrom());
						echo "<tr class=\"linkable\"><td class=\"docNumber\">".$mattran->prepareDocNumber()."</td>";
						echo "<td>".$mattran->prepareDocDate("j F Y")."</td>";
						echo "<td>".$mattran->prepareRequester()."</td>";
						echo "<td>".$fromBranch->prepareName()."</td>";
						echo "<td>".$toBranch->prepareName()."</td>";
						echo "<td>".$mattran->prepareStatus()."</td></tr>";
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
	<h3>List - Completed</h3>
	<table>
		<thead>
			<tr>
				<th>Document Number</th><th>Document Date</th><th>Requester</th>
				<th>from Department</th><th>to Department</th><th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				try{
					$mattrans = Material_transfer::findAllComplete(20);
					foreach($mattrans as $mattran)
					{
						//$branch = new Branch($mattran->getBranchId());
						$toBranch = new Branch($mattran->getBranchTo());
						$fromBranch = new Branch($mattran->getBranchFrom());
						echo "<tr class=\"linkable\"><td class=\"docNumber\">".$mattran->prepareDocNumber()."</td>";
						echo "<td>".$mattran->prepareDocDate("j F Y")."</td>";
						echo "<td>".$mattran->prepareRequester()."</td>";
						echo "<td>".$fromBranch->prepareName()."</td>";
						echo "<td>".$toBranch->prepareName()."</td>";
						echo "<td>".$mattran->prepareStatus()."</td></tr>";
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