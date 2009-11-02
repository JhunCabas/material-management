<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-grn.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Goods Receipt Note</h2>
	<?php 
		if(!isSet($_GET['id']))
		{
	?>
		<form action="document-grn.php" method="get" accept-charset="utf-8">
			<label for="id">Purchase Order No: </label><input type="text" name="id" id="poInput" />
			<p><input type="submit" value="Continue &rarr;"></p>
		</form>
	<?php
		}else{
			try{
				$purchase = new Purchase($_GET['id']);
	?>
	<div class="form-frame span-23 last">
		<h3>Goods Receipt Note</h3><br />
		<input type="hidden" name="run_num" value="" id="run_num"/>
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label>GRN <input id="doc_type" type="hidden" value="GRN"></input><br />
		<label for="branch_id">Branch </label>
			<select type="text" name="branch_id" id="branch_id"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($user->getBranchId()); ?></select><br />
			<input type="hidden" id="hiddenBranch" value="<?php echo $user->prepareBranchId();?>" />
		<div class="supplierBox span-23 last">
			<div id="box1" class="boxes span-7">
				<b>Supplier</b><br />
					<?php $supplier = new Supplier($purchase->getSupplier1()); echo $supplier->prepareName(); ?>
					<input type="hidden" id="supplierID" value="<?php echo $purchase->prepareSupplier1(); ?>"></input>
				<br />
				<div class="boxBody">
					<?php Supplier::generateInfo($purchase->getSupplier1()); ?>
				</div>
			</div>
			<div class="span-14 last">
			<table>
				<tr>
					<td><label>PO No </label></td>
					<td><label>DO No </label></td>
				</tr>
				<tr>
					<td><input type="text" id="poNo" value="<?php echo $_GET['id']; ?>" readonly="true"></input></td>
					<td><input type="text" id="doNo"></input></td>
				</tr>
			</table>
			</div>
		</div>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th>(A)</th></tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<!--<tr><td colspan="7" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td></tr>-->
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
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Inspected & Received By </label></td><td id="inspector">
						<?php echo fAuthorization::getUserToken();?><input type="hidden" id="inspectorID" value="<?php echo fAuthorization::getUserToken();?>">
					</td><td><label>Date </label><input type="text" id="insDate" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php 
				$me = fAuthorization::getUserToken(); echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>";
				}catch	(fExpectedException $e) {
					echo $e->printMessage();
				}
			}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>