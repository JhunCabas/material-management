<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-pi.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Production Issue Form</h2>
	<div class="form-frame span-23 last">
		<h3>Production Issue Form</h3><br />
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label><select type="text" name="doc_type" value="" id="doc_type">
			<?php Document_type::findAllOption();?></select><br />
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
					<td><label>Issued by </label></td><td id="issuer"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="issDate" class="datepicker"></input></td>
					<td><label>Received by </label></td><td id="receiver"><input type="button" value="Sign Here" class="signHere" /></td><td><label>Date </label><input type="text" id="recDate" class="datepicker"></input></td>
				</tr>
		</table>
		<input type="button" id="submitBTN" value="Submit" style="float: right;"/>
		<?php $me = fAuthorization::getUserToken(); echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>"?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>