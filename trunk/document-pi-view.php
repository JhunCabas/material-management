<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-pi-view.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php 
		$tmpl->place('menuDocument');
		if(!isSet($_GET['id']))
		{
			echo "<div class=\"span-24 ui-state-error ui-corner-all\">
					<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: 30px;\"></span>
					You arrived to this page in error</div>";
		}else{
			try{
				$production = new Production_issue($_GET['id']);
				$production_details = Production_issue_detail::findDetail($_GET['id']);
	?>
	<h2>Production Issue Form</h2>
	<div class="form-frame span-23 last">
		<h3>Production Issue Form</h3><br />
		<label for="doc_num">Document Number </label><?php echo $production->prepareDocNumber(); ?><br />
		<label for="doc_date">Document Date </label><?php echo $production->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label><?php echo $production->prepareDocType(); ?><br />
		<label for="branch_id">Branch </label>
				<?php $branch = new Branch($production->getBranchId()); echo $branch->prepareName() . " / " . $production->prepareBranchId();?><br />
		<table id="formContent">
			<thead>
				<tr><td>No</td>
					<td>Item Code</td><td width="400">Description</td><td>Quantity</td><td>UOM</td><td>Remarks</td></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					foreach($production_details as $production_detail)
					{
						echo "<td>".$counter."</td><td>".$production_detail->prepareItemId()."</td>";
						$item = new Inv_item($production_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td>".$production_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$production_detail->prepareRemark()."</td>";
						$counter++;
					}
				?>
			</tbody>
			<tfoot>
				<tr><td colspan="6" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td></tr>
			</tfoot>
		</table>
		<label>Notes</label><br /><?php echo $production->prepareNotes(); ?><br />
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Issued by </label></td><td id="issuer"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="issDate" class="datepicker"></input></td>
					<td><label>Received by </label></td><td id="receiver"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="recDate" class="datepicker"></input></td>
				</tr>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php 
					$me = fAuthorization::getUserToken(); 
					echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>";
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>