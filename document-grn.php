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
	<div class="form-frame span-23 last">
		<h3>Goods Receipt Note</h3><br />
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label><select type="text" name="doc_type" value="" id="doc_type">
				<?php Document_type::findAllOption();?></select><br />
		<label for="branch_id">Branch </label>
					<select type="text" name="branch_id" id="branch_id"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($user->getBranchId()); ?></select><br />
		<p>
		<table>
			<tr><td><label>Supplier </label></td>
				<td><label>DO No </label></td>
				<td><label>PO No </label></td>
			</tr>
			<tr><td><input type="text" id="supplierAuto"></input><input type="hidden" id="supplier"/></td>
				<td><input type="text" id="doNo"></input></td>
				<td><input type="text" id="poNo"></input></td>
			</tr>
		</table>
		</p>
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th>(A)</th></tr>
			</thead>
			<tbody>
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
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Inspected By </label></td><td id="inspector"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="insDate" class="datepicker"></input></td>
				</tr>
				<tr>
					<td><label>Received By </label></td><td id="receiver"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="recDate" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php $me = fAuthorization::getUserToken(); echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>"?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>