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
		$counter = 1;
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
	<h2>Purchase Request</h2><br>
  <div align=left> <a href=report/pr-pdf.php?PRnum=<?=$_GET['id']?>><b>Download PDF</b></a></div><br>
	<div class="form-frame span-23 last">
		<h3>Purchase Request</h3><br />
		<label for="doc_num">Document Number </label>
			<span id="docNum"><?php echo $purchase->prepareDocNumber(); ?></span>
		<label class="mofLabel" for="mof_num">MOF Number</label>
			<span id="mof_num">
			<?php 
				if($purchase->getPoNumber() != NULL)
					echo '<a href="document-po-view.php?id='.$purchase->prepareDocNumber().'">'.$purchase->prepareMofNumber().'</a>';
				else
					echo $purchase->prepareMofNumber(); 
			?></span>
		<br />
		<label for="doc_date">Document Date </label>
			<span id="doc_date"><?php echo $purchase->prepareDocDate("j F Y"); ?></span><br />
		<label for="doc_type">Document Type </label>
			<?php echo strtoupper($purchase->prepareDocTag()).$purchase->prepareDocType(); ?><br />
		<label for="branch_id">Ship To Branch </label>
			<select type="text" name="branch_id" id="branch_id" style="display: inline;"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($purchase->prepareBranchId()); ?></select>
			<span id="branch_view" class="tochange"><?php $branch = new Branch($purchase->getBranchId()); echo $branch->prepareName() . " / " . $purchase->prepareBranchId();?></span><br />
		<label for="currency_id">Currency </label>
			<select type="text" name="currency_id" id="currency_id" style="display: inline;"></select>
			<span id="currency_view" class="tochange"><?php $currency = new Currency($purchase->getCurrency()); echo $currency->prepareCountry()." [".$currency->prepareExchange(2)."]"; ?></span>
		<div class="supplierBox span-23 last">
			<div id="box1" class="boxes span-7">
				<b>Supplier 1</b><br />
					<?php $supplier = new Supplier($purchase->getSupplier1()); echo $supplier->prepareName(); ?>
				<br />
				<div class="boxBody">
					<?php Supplier::generateInfo($purchase->getSupplier1()); ?>
				</div>
			</div>
			<?php if($purchase->getSupplier2()!=null) {?>
			<div id="box2" class="boxes span-7">
				<b>Supplier 2</b><br />
					<?php $supplier2 = new Supplier($purchase->getSupplier2()); echo $supplier2->prepareName(); ?>
				<br />
				<div class="boxBody">
					<?php Supplier::generateInfo($purchase->getSupplier2()); ?>
				</div>
			</div>
			<?php } if($purchase->getSupplier3()!=null) {?>
			<div id="box3" class="boxes span-7 last">
				<b>Supplier 3</b><br />
					<?php $supplier3 = new Supplier($purchase->getSupplier3()); echo $supplier3->prepareName(); ?>
				<br />
				<div class="boxBody">
					<?php Supplier::generateInfo($purchase->getSupplier3()); ?>
				</div>
			</div>
			<?php } ?>
		</div>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Unit Price</th><th>Extended Price</th></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					$totalchecker = 0;
					foreach($purchase_details as $purchase_detail)
					{
						//echo "<tr><td>".$counter."</td><td>".$purchase_detail->prepareItemId()."</td>";
						//$item = new Inv_item($purchase_detail->getItemId());
						//echo "<td>".$item->prepareDescription()."</td><td>".$purchase_detail->prepareQuantity()."</td>
						//	 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$purchase_detail->getUnitPrice()."</td>
						//		<td>".$purchase_detail->getExtendedPrice()."</td></tr>";
						$totalchecker = $totalchecker + $purchase_detail->getExtendedPrice();
						$counter++;
					}
				?>
			</tbody>
				<?php
				$totalchecker = $totalchecker - $purchase->getDiscount();
				if($totalchecker != $purchase->getTotal())
				{
					$purchaseNu = new Purchase($_GET['id']);
					$purchaseNu->setTotal($totalchecker);
					$purchaseNu->store();
				}
				?>
			<tfoot>
				<tr><td colspan="5"></td><td>Discount</td><td><input type="text" id="discountRate" value="<?php echo $purchase->prepareDiscount(2); ?>"></input></td></tr>
				<tr><td colspan="5" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td><td class="tfootCaption">Total</td><td id="purchaseTotal"><?php echo $purchase->prepareTotal(2); ?></td></tr>
			</tfoot>
		</table>
		<div class="pdbox span-11"><label>Payment Terms</label><br /><textarea id="payment" rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"><?php echo $purchase->preparePayment(); ?></textarea></div>
		<div class="pdbox span-11"><label>Delivery Terms</label><br /><textarea id="delivery" rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"><?php echo $purchase->prepareDelivery(); ?></textarea></div>
		<div class="pdbox span-11"><label>Special Instructions</label><br /><textarea id="special" rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"><?php echo $purchase->prepareSpecialInstruction(); ?></textarea></div>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo $purchase->prepareRequester(); ?></td><td><label>Date </label><?php echo $purchase->prepareRequesterDate("j F Y"); ?></td>
				</tr>
				<tr>
					<td><label>Approver</label></td><td id="approver1"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="app1Date" class="datepicker"></input></td>
				</tr>
				<tr style="display: none;">
					<td><label>Approver 2 </label></td><td></td><td><label>Date </label><input type="text" id="app2Date" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
		<input type="button" id="cancelBTN" value="Cancel" style="float: right;"/>
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