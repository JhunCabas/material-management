<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-pi.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Production Issue Form</h2>
	<div class="form-frame span-23 last">
		<h3>Production Issue Form</h3><br />
		<input type="hidden" name="run_num" value="" id="run_num"/>
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><span id="loaderBar"> <img src="img/layout/ajax-loaderBar.gif"/> Loading </span><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label>
			<select type="text" name="doc_type" value="" id="doc_type">
				<option value="PI1">PI1 - Asset Disposal</option>
				<option value="PI2">PI2 - Material Issue</option>
				<option value="PI3">PI3 - Not required</option>
			</select><br />
		<label for="branch_id">Branch </label>
				<select type="text" name="branch_id" id="branch_id"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($user->getBranchId()); ?></select><br />
				<input type="hidden" id="hiddenBranch" value="<?php echo $user->prepareBranchId();?>" />
		<table id="formContent">
			<thead>
				<tr><td>No</td>
					<td>Item Code</td><td width="400">Description</td><td>Quantity</td><td>UOM</td><td>Remarks</td></tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr><td colspan="6" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td></tr>
			</tfoot>
		</table>
		<label>Notes</label><br /><textarea id="notes"rows="4" cols="10" style="height: 30px; margin-bottom: 20px;"></textarea>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Issued and Received by </label></td><td id="issuer"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="issDate" class="datepicker"></input></td>
				</tr>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php $me = fAuthorization::getUserToken(); echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>"?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>