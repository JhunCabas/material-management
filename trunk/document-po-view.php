<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-po-view.js"></script>
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
				$purchase = new Purchase($_GET['id']);
				$purchase_details = Purchase_detail::findDetail($_GET['id']);
	?>
	<h2>Purchase Order</h2> <a href=report/savefile.php?POnum=<?=$_GET['id']?>><b>Download as Word</b></a><br>
	<div class="form-frame span-23 last">
		<h3>Purchase Order</h3><br />
		<label for="doc_num">Document Number </label>
			<?php echo $purchase->prepareDocNumber(); ?><br />
		<label for="doc_date">Document Date </label>
			<?php echo $purchase->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label>
			<?php echo $purchase->prepareDocType(); ?><br />
		<label for="branch_id">Branch </label>
			<?php $branch = new Branch($purchase->getBranchId()); echo $branch->prepareName() . " / " . $purchase->prepareBranchId();?><br />
		<p>
		<table>
			<tr><td>
					<label>Supplier 1 </label>
					<?php $supplier = new Supplier($purchase->getSupplier1()); echo $supplier->prepareName(); ?>
				</td>
				<td><label>Contact Person </label><?php echo $purchase->prepareSupplier1Contact(); ?></td>
				<td><label>Tel No </label><?php echo $purchase->prepareSupplier1Tel(); ?></td>
			</tr>
			<tr><td>
					<label>Supplier 2 </label>
					<?php 
						if($purchase->getSupplier2() != null)
						{
							$supplier = new Supplier($purchase->getSupplier2()); 
							echo $supplier->prepareName();
						} 
					?>
				</td>
				<td><label>Contact Person </label><?php echo $purchase->prepareSupplier2Contact(); ?></td>
				<td><label>Tel No </label><?php echo $purchase->prepareSupplier2Tel(); ?></td>
			</tr>
			<tr><td>
					<label>Supplier 3 </label>
					<?php 
						if($purchase->getSupplier3() != null)
						{
							$supplier = new Supplier($purchase->getSupplier3()); 
							echo $supplier->prepareName(); 
						}
					?>
				</td>
				<td><label>Contact Person </label><?php echo $purchase->prepareSupplier3Contact(); ?></td>
				<td><label>Tel No </label><?php echo $purchase->prepareSupplier3Tel(); ?></td>
			</tr>
		</table>
		</p>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Unit Price</th><th>Extended Price</th></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					foreach($purchase_details as $purchase_detail)
					{
						echo "<tr><td>".$counter."</td><td>".$purchase_detail->prepareItemId()."</td>";
						$item = new Inv_item($purchase_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td>".$purchase_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$purchase_detail->prepareUnitPrice()."</td>
								<td>".$purchase_detail->prepareExtendedPrice()."</td></tr>";
						$counter++;
					}
				?>
			</tbody>
			<tfoot>
				<tr><td colspan="5"></td><td>Discount</td><td><?php echo $purchase->prepareDiscount(2); ?></td></tr>
				<tr><td colspan="5" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td><td class="tfootCaption">Total</td><td id="purchaseTotal"><?php echo $purchase->prepareTotal(2); ?></td></tr>
			</tfoot>
		</table>
		<div class="pdbox span-11"><label>Payment Terms</label><br /><?php echo $purchase->preparePayment(); ?></div>
		<div class="pdbox span-11"><label>Delivery Terms</label><br /><?php echo $purchase->prepareDelivery(); ?></div>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo $purchase->prepareRequester(); ?></td><td><label>Date </label><?php echo $purchase->prepareRequesterDate("j F Y"); ?></td>
				</tr>
				<tr>
					<td><label>Approver 1 </label></td><td id="approver"><?php echo $purchase->prepareApprover1(); ?></td><td><label>Date </label><?php echo $purchase->prepareApprover1Date("j F Y"); ?></td>
				</tr>
				<tr style="display: none;">
					<td><label>Approver 2 </label></td><td></td><td><label>Date </label><input type="text" id="app2Date" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
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