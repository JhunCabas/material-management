<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-pr.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Purchase Request</h2>
	<div class="form-frame span-23 last">
		<h3>Purchase Request</h3><br />
			<input type="hidden" name="run_num" value="" id="run_num"/>
		<label for="doc_num">Document Number </label>
			<input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label>
			<input type="text" name="doc_date" value="" id="doc_date" /><br />
		<label for="doc_type">Document Type </label>
			<select type="text" name="doc_type" value="" id="doc_type">
				<option value="PR1">PR1 – Purchase Request (Assets)</option>
				<option value="PR2">PR2 – Purchase Request (Stocks)</option>
				<option value="PR3">PR3 – Purchase Request (Service)</option>
				<option value="PR4">PR4 – Purchase Request (3rd Party)</option>
			</select><br />
		<label for="branch_id">Branch </label>
			<select type="text" name="branch_id" id="branch_id"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($user->getBranchId()); ?></select><br />
			<label for="currency_id">Currency </label>
				<select type="text" name="currency_id" id="currency_id">
				</select><br />
		<div class="supplierBox span-23 last">
			<div id="box1" class="boxes span-7">
				<b>Supplier 1</b><br />
				<input type="text" id="sup1auto"></input><input type="hidden" id="sup1"></input>
				<br />
				<div class="boxBody">
				</div>
			</div>
			<div id="box2" class="boxes span-7">
				<b>Supplier 2</b><br />
				<input type="text" id="sup2auto"></input><input type="hidden" id="sup2"></input>
				<br />
				<div class="boxBody">
				</div>
			</div>
			<div id="box3" class="boxes span-7 last">
				<b>Supplier 3</b><br />
				<input type="text" id="sup3auto"></input><input type="hidden" id="sup3"></input>
				<br />
				<div class="boxBody">
				</div>
			</div>
		</div>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Unit Price</th><th>Extended Price</th></tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr><td colspan="5"></td><td>Discount</td><td><input type="text" id="discountRate" value="0.00"></input></td></tr>
				<tr><td colspan="5" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td><td class="tfootCaption">Total</td><td id="purchaseTotal"></td></tr>
			</tfoot>
		</table>
		<div class="pdbox span-11"><label>Payment Terms</label><br /><textarea id="payment" rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"></textarea></div>
		<div class="pdbox span-11"><label>Delivery Terms</label><br /><textarea id="delivery" rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"></textarea></div>
		<div class="pdbox span-11"><label>Special Instructions</label><br /><textarea id="special" rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"></textarea></div>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo fAuthorization::getUserToken();?></td><td><label>Date </label><input type="text" id="reqDate" class="datepicker"></input></td>
				</tr>
				<tr style="display: none;">
					<td><label>Approver 1 </label></td><td></td><td><label>Date </label><input type="text" id="app1Date" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
		<input type="button" id="submitPOBTN" value="Submit to PO" style="float: left;">
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
	</div>
</div>
<?php $tmpl->place('footer'); ?>