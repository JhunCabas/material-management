<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-mtr-view.js"></script>
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
				$mattrans = new Material_transfer($_GET['id']);
				$mattrans_details = Material_transfer_detail::findDetail($_GET['id']);
	?>
	<h2>Material Transfer</h2>
	<div class="form-frame span-23 last">
		<h3>Material Transfer Form</h3><br />
		<label for="doc_num">Document Number </label>
			<?php echo $mattrans->prepareDocNumber(); ?><input id="doc_num" type="hidden" value="<?php echo $mattrans->prepareDocNumber(); ?>"></input><br />
		<label for="doc_date">Document Date </label>
			<?php echo $mattrans->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label>
			<?php echo $mattrans->prepareDocType(); ?><br />
		<label for="branch_id">From Branch </label>
				<?php $fromBranch = new Branch($mattrans->getBranchFrom()); echo $fromBranch->prepareName();?>
		<label for="branch_id"> To Branch </label>
			<?php $toBranch = new Branch($mattrans->getBranchTo()); echo $toBranch->prepareName();?><br />
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th style="width: 120px;">Quantity in <?php echo $fromBranch->prepareName(); ?></th></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					foreach($mattrans_details as $mattrans_detail)
					{
						echo "<tr class=\"jsonRow\"><td>".$counter."</td><td class=\"itemCode\">".$mattrans_detail->prepareItemId().
							"<input class=\"itemId\" type=\"hidden\" value=\"".$mattrans_detail->prepareId()."\"></input></td>";
						$item = new Inv_item($mattrans_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td class=\"itemQuan\">".$mattrans_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$mattrans_detail->prepareRemark()."</td>";
						/*
						echo "<td><select id=\"fromBranch\">";
						$tempRecords = Inv_stock::findByStock($mattrans_detail->getItemId(),$mattrans_detail->getQuantity());
						foreach($tempRecords as $tempRecord)
						{
							$branch = new Branch($tempRecord->getBranchId());
							fHTML::printOption($branch->prepareName().
								"[".$tempRecord->prepareQuantity()."]",$tempRecord->prepareBranchId());
						}
						echo "</select></td>";
						*/
						$tempRecords = Inv_stock::findStockByBranch($mattrans_detail->getItemId(),$mattrans->getBranchFrom());
						foreach($tempRecords as $tempRecord)
						{
							echo "<td>".$tempRecord->prepareQuantity()."</td>";
						}
						echo "</tr>";
						$counter++;
					}
				?>
			</tbody>
		</table>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo $mattrans->prepareRequester(); ?></td><td><label>Date </label><?php echo $mattrans->prepareRequesterDate("j F Y"); ?></td>
				</tr>
			</tbody>
		</table>
		<?php 
					if($mattrans->getStatus() == 'pending')
						echo "<input type=\"button\" id=\"submitBTN\" value=\"Submit\" style=\"float: right;\"/>";
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