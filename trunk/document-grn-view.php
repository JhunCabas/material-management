<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-grn-view.js"></script>
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
				$grn = new Good_receipt_note($_GET['id']);
				$grn_details = Good_receipt_note_detail::findDetail($_GET['id']);
	?>
	<h2>Goods Receipt Note</h2>
	<div class="form-frame span-23 last">
		<h3>Goods Receipt Note</h3><br />
		<label for="doc_num">Document Number </label>
			<span id="doc_num"><?php echo $grn->prepareDocNumber(); ?></span><br />
		<label for="doc_date">Document Date </label>
			<?php echo $grn->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label>
			<?php echo $grn->prepareDocType(); ?><br />
		<label for="branch_id">Branch </label>
			<?php $branch = new Branch($grn->getBranchId()); echo $branch->prepareName() . " / " . $grn->prepareBranchId();?><br />
		<p>
		<table>
			<tr><td><label>Supplier </label></td>
				<td><label>DO No </label></td>
				<td><label>PO No </label></td>
			</tr>
			<tr><td><?php $supplier = new Supplier($grn->getSupplier()); echo $supplier->prepareName(); ?></td>
				<td><?php echo $grn->prepareDoNo(); ?></td>
				<td><?php echo $grn->preparePoNo(); ?></input></td>
			</tr>
		</table>
		</p>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th>(A)</th></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					foreach($grn_details as $grn_detail)
					{
						echo "<td>".$counter."</td><td>".$grn_detail->prepareItemId()."</td>";
						$item = new Inv_item($grn_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td>".$grn_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$grn_detail->prepareRemark()."</td>
								<td class=\"assessText\">".$grn_detail->prepareAssessment()."</td>";
						$counter++;
					}
				?>
			</tbody>
			<tfoot>
				<tr><td colspan="7" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td></tr>
				<tr><td colspan="7">
					<p>
						<label>Assesment Of Condition (A)</label><br />
						<b>OK</b> : Item received in good condition<br />
						<b>NG</b> : Item is in damaged condition<br />
						<b>Q </b> : Quantity supplied did not match DO quantity<br />
						<b>X </b> : Item sent is different from PO description
					</p>
					</td>
				</tr>
			</tfoot>
		</table>
		<?php echo "<input type=\"hidden\" id=\"lastCount\" value=\"".$counter."\"></input>";?>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Inspected by </label></td>
						<?php 
							if($grn->getInspector()!=null)
								echo "<td>".$grn->prepareInspector()."</td>";
							else
								echo "<td id=\"inspector\"><input type=\"button\" value=\"Sign Here\" class=\"signHere\" /></td>";
						?></td><td><label>Date </label>
						<?php 
							if($grn->getInspector_date()!=null)
								echo $grn->prepareInspector_date("j F Y");
							else
								echo "<input type=\"text\" id=\"insDate\" class=\"datepicker\"></input>";
						?></td>
					<td><label>Received by </label></td>
						<?php
							if($grn->getReceiver()!=null)
								echo "<td>".$grn->prepareReceiver()."</td>";
							else
								echo "<td id=\"receiver\"><input type=\"button\" value=\"Sign Here\" class=\"signHere\" /></td>";
						?></td><td><label>Date </label>
						<?php
							if($grn->getReceiverDate()!=null)
								echo $grn->prepareReceiverDate("j F Y");
							else
								echo "<input type=\"text\" id=\"recDate\" class=\"datepicker\"></input>";
						?></td>
				</tr>
			</tbody>
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