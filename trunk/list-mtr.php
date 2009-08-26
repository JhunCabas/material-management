<?php
include './resources/init.php';
$tmpl->place('header');
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Material Transfer Note</h2>
	<h3>Add Material Transfer Request : <a href="document-mtr.php">Form</a></h3>
	<h3>List</h3>
	<table>
		<thead>
			<tr><th>Document Number</th><th>Document Date</th><th>From Department</th><th>Item Code</th><th>Quantity</th></tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>