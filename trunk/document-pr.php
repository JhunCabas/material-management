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
		<div class="span-12">
			<label for="doc_num">Document Number </label> 
				<input type="text" name="doc_num" size="30" id="doc_num"/> <span id="loaderBar"><img src="img/layout/ajax-loaderBar.gif"/> Loading </span>
		</div>
		<div class="mofbox span-10 last">			
			<div id="mofinput" class="span-10">				
				<div class="span-9">	
					<label for="moffinal">MOF Number: </label>				
					<input type="text" maxlength="3" name="mof1" size="3" id="mof1"/>/
					<input type="text" maxlength="3" name="mof2" size="3" id="mof2"/>/
					<input type="text" maxlength="2" name="mof3" size="2" id="mof3"/>/
					<input type="text" maxlength="4" name="mof4" size="4" id="mof4"/>
				</div>
				<div id="mofenter" class="span-1 ui-icon ui-icon-circle-plus"></div>
			</div>
			<div id="moffinal" class="span-10">
				<div class="span-9">
					<label for="moffinal">MOF Number: </label>	
					<input type="text" name="moftext" size="30" id="moftext"/>				
				</div>
				<div id="mofedit" class="span-1 ui-icon ui-icon-pencil"></div>
			</div>		
		</div>
			<br />
		<div class="span-12">
		<label for="doc_date">Document Date </label>
			<input type="text" name="doc_date" value="" id="doc_date" /><br />
		<label for="doc_type">Document Type </label>
			<select type="text" name="doc_type" value="" id="doc_type">
				<option value="1">PR1 – Purchase Request (Assets)</option>
				<option value="2">PR2 – Purchase Request (Stocks)</option>
				<option value="3">PR3 – Purchase Request (Service)</option>
				<option value="4">PR4 – Purchase Request (PCSB)</option>
				<option value="5">PR5 - Purchase Request (3rd Party)</option>
				<option value="6">PR6 - Purchase Request (GD9)</option>
				<option value="7">PR7 - Purchase Request</option>
				<option value="8">PR8 - Purchase Request</option>
			</select><br />
		<label for="branch_id">Ship To Branch </label>
			<select type="text" name="branch_id" id="branch_id"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($user->getBranchId()); ?></select><br />
			<input type="hidden" id="hiddenBranch" value="<?php echo $user->prepareBranchId();?>" />
			<label for="currency_id">Currency </label>
				<select type="text" name="currency_id" id="currency_id">
				</select><br />
		</div>
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