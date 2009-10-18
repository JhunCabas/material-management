<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-mtr.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Material Transfer</h2>
	<div class="form-frame span-23 last">
		<h3>Material Transfer Form</h3><br />
		<input type="hidden" name="run_num" value="" id="run_num"/>
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label>MTF <input id="doc_type" type="hidden" value="MTF"></input><br />
		<label for="branch_id">Branch </label>
						<select type="text" name="branch_id" id="branch_id"><?php $user = new User(fAuthorization::getUserToken()); Branch::findAllOption($user->getBranchId()); ?></select><br />
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="300px">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th></tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr><td colspan="7" id="addRowBTN"><div class="ui-icon ui-icon-circle-plus span-1 last"></div>Add Row</td></tr>
			</tfoot>
		</table>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td id="requester"><?php echo fAuthorization::getUserToken();?></td><td><label>Date </label><input type="text" id="reqDate" class="datepicker"></input></td>
					<tr style="display: none;">
						<td><label>Approver </label></td><td id="approver"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="appDate" class="datepicker"></input></td>
					</tr>
				</tr>
			</tbody>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php $me = fAuthorization::getUserToken(); echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>"?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>