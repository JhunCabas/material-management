<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
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
  <div align=left> <a href=report/grn-pdf.php?GRNnum=<?=$_GET['id']?>><b>Download PDF</b></a></div><br>
	<div class="form-frame span-23 last">
		<h3>Goods Receipt Note</h3><br />
		<label for="doc_num">Document Number </label>
			<span id="doc_num"><?php echo $grn->prepareDocNumber(); ?></span><br />
			<input id="grnNo" type="hidden" value="<?php echo $grn->prepareDocNumber(); ?>"/>
		<label for="doc_date">Document Date </label>
			<?php echo $grn->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label>
			<?php echo $grn->prepareDocType(); ?><br />
		<label for="branch_id">Branch </label>
			<?php $branch = new Branch($grn->getBranchId()); echo $branch->prepareName() . " / " . $grn->prepareBranchId();?><br />
			<div class="supplierBox span-23 last">
				<div id="box1" class="boxes span-7">
					<b>Supplier</b><br />
						<?php $supplier = new Supplier($grn->getSupplier()); echo $supplier->prepareName(); ?>
						<input type="hidden" id="supplier" val="<?php echo $grn->prepareSupplier(); ?>"></input>
					<br />
					<div class="boxBody">
						<?php Supplier::generateInfo($grn->getSupplier()); ?>
					</div>
				</div>
				<div class="span-14 last">
				<table>
					<tr>
						<td><label>PO No </label></td>
						<td><label>DO No </label></td>
					</tr>
					<tr>
						<td><?php echo $grn->preparePoNo(); ?></td>
						<td><?php echo $grn->prepareDoNo(); ?></td>
					</tr>
				</table>
				</div>
			</div>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th>(A)</th></tr>
			</thead>
			<!--
			<tbody>
				<?php
					$counter = 1;
					foreach($grn_details as $grn_detail)
					{
						echo "<tr><td>".$counter."</td><td>".$grn_detail->prepareItemId()."</td>";
						$item = new Inv_item($grn_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td>".$grn_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$grn_detail->prepareRemark()."</td>
								<td class=\"assessText\">".$grn_detail->prepareAssessment()."</td></tr>";
						$counter++;
					}
				?>
			</tbody>
			-->
			<tbody>
			</tbody>
			<tfoot>
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
				<?php if($grn->getStatus() != "incomplete") {?>
				<tr>
					<td><label>Inspected and Received by </label></td>
						<?php 
							echo "<td>".$grn->prepareInspector()."</td>";
						?></td><td><label>Date </label>
						<?php 
							echo $grn->prepareInspector_date("j F Y");
						?></td>
				</tr>
				<?php }else{?>
				<tr>
					<td><label>Inspected & Received By </label></td><td id="inspector">
						<?php echo fAuthorization::getUserToken();?><input type="hidden" id="inspectorID" value="<?php echo fAuthorization::getUserToken();?>">
					</td><td><label>Date </label><input type="text" id="insDate" class="datepicker"></input></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<?php if($grn->getStatus() == "incomplete") {?>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php 
			}
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