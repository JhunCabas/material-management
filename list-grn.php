<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" charset="utf-8">
	$(function (){
		$("tr.linkable").click(function (){
			window.location = "document-grn-view.php?"+"id="+$(this).children(".docNumber").text();
		});
	});
</script>
<?php
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
				$user = new User(fAuthorization::getUserToken());
				try{
					if(fAuthorization::checkAuthLevel('admin'))
						$grnEntries = Good_receipt_note::findAll(20);
					else
						$grnEntries = Good_receipt_note::findAllByBranch($user->prepareBranchId(),20);
					foreach($grnEntries as $grnEntry)
					{
						echo "<tr class=\"linkable\"><td class=\"docNumber\">".$grnEntry->prepareDocNumber()."</td>";
						echo "<td>".$grnEntry->prepareDocDate("j F Y")."</td>";
						echo "<td>".$grnEntry->prepareDoNo()."</td>";
						echo "<td>".$grnEntry->preparePoNo()."</td>";
						echo "<td>".$grnEntry->prepareStatus()."</td></tr>";
					}
				}catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>