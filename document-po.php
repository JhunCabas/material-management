<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-pr.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Purchase Order</h2>
	<div class="form-frame span-23 last">
		<h3>Purchase Order</h3><br />
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label>
			<select type="text" name="doc_type" value="" id="doc_type"><?php Document_type::findAllOption();?></select><br />
		<p>
		<table>
			<tr><td><label>Supplier 1 </label><input type="text" id="sup1"></input></td>
				<td><label>Contact Person </label><input type="text" id="con1"></input></td>
				<td><label>Tel No </label><input type="text" id="tel1"></input></td>
			</tr>
			<tr><td><label>Supplier 2 </label><input type="text" id="sup2"></input></td>
				<td><label>Contact Person </label><input type="text" id="con2"></input></td>
				<td><label>Tel No </label><input type="text" id="tel2"></input></td>
			</tr>
			<tr><td><label>Supplier 3 </label><input type="text" id="sup3"></input></td>
				<td><label>Contact Person </label><input type="text" id="con3"></input></td>
				<td><label>Tel No </label><input type="text" id="tel3"></input></td>
			</tr>
		</table>
		</p>
		<table id="formContent">
			<thead>
				<tr><td>No</td>
					<td>Item Code</td><td>Description</td><td>Quantity</td><td>UOM</td><td>Unit Price</td><td>Extended Price</td></tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
				<tr><td colspan="6" class="tfootCaption">Total</td><td id="purchaseTotal"></td></tr>
			</tfoot>
		</table>
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Requester </label></td><td></td><td><label>Date </label><input type="text" id="reqDate" class="datepicker"></input></td>
				</tr>
				<tr>
					<td><label>Approver 1 </label></td><td></td><td><label>Date </label><input type="text" id="app1Date" class="datepicker"></input></td>
				</tr>
				<tr>
					<td><label>Approver 2 </label></td><td></td><td><label>Date </label><input type="text" id="app2Date" class="datepicker"></input></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php $tmpl->place('footer'); ?>