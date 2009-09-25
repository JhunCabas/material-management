<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-pr-view.js"></script>
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
	<h2>Purchase Request</h2>
	<div class="form-frame span-23 last">
		<h3>Purchase Request</h3><br />
		<label for="doc_num">Document Number </label>
			<span id="docNum"><?php echo $purchase->prepareDocNumber(); ?></span><br />
		<label for="doc_date">Document Date </label>
			<?php echo $purchase->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label>
			<?php echo $purchase->prepareDocType(); ?><br />
		<label for="branch_id">Branch </label>
			<?php $branch = new Branch($purchase->getBranchId()); echo $branch->prepareName() . " / " . $purchase->prepareBranchId();?><br />
		<label for="currency_id">Currency </label>
				<?php $currency = new Currency($purchase->getCurrency()); echo $currency->prepareCountry()." [".$currency->prepareExchange(2)."]"; ?>
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
						echo "<tr class=\"jsonRow\" id=\"rowNo".$counter."\"><td>".$counter."<input type=\"hidden\" class=\"detailId\" value=\"".$purchase_detail->prepareId()."\"></input></td><td><input class=\"itemCode\" value=\"".$purchase_detail->prepareItemId()."\"></input></td>";
						$item = new Inv_item($purchase_detail->getItemId());
						echo "<td class=\"descAuto\">".$item->prepareDescription()."</td><td><input class=\"itemQuan\" value=\"".$purchase_detail->prepareQuantity()."\"></input></td>
							 	<td class=\"uomAuto\">".$item->prepareUnitOfMeasure()."</td><td><input class=\"itemUnitP\" value=\"".$purchase_detail->prepareUnitPrice()."\"></input></td>
								<td><input class=\"itemExtP\" value=\"".$purchase_detail->prepareExtendedPrice()."\"></input></td></tr>";
						$counter++;
					}
				?>
			</tbody>
			<tfoot>
				<tr><td colspan="5"></td><td>Discount</td><td><input type="text" id="discountRate" value="<?php echo $purchase->prepareDiscount(2); ?>"></input></td></tr>
				<tr><td colspan="5" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td><td class="tfootCaption">Total</td><td id="purchaseTotal"><?php echo $purchase->prepareTotal(2); ?></td></tr>
			</tfoot>
		</table>
		<div class="span-11"><label>Payment Terms</label><br /><?php echo $purchase->preparePayment(); ?></div>
		<div class="span-11"><label>Delivery Terms</label><br /><?php echo $purchase->prepareDelivery(); ?></div>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo $purchase->prepareRequester(); ?></td><td><label>Date </label><?php echo $purchase->prepareRequesterDate("j F Y"); ?></td>
				</tr>
				<tr>
					<td><label>Approver 1 </label></td><td id="approver1"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="app1Date" class="datepicker"></input></td>
				</tr>
				<tr style="display: none;">
					<td><label>Approver 2 </label></td><td></td><td><label>Date </label><input type="text" id="app2Date" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php 
					$me = fAuthorization::getUserToken(); 
					echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>";
					echo "<input type=\"hidden\" id=\"docStatus\" value=\"".$purchase->prepareStatus()."\"/>";
					echo "<input type=\"hidden\" id=\"lastCounter\" value=\"".$counter."\"/>";
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>