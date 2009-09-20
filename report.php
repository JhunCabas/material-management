<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-branch.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuReport'); ?>
	<h2>Report</h2><h3>List</h3>
	<ol>
		<li><a href="report/ViewChart.php?op=itempurchaseyear" target="_blank">Num of Inventory Items vs Purchased Year</a></li>
	    <li><a href="report/ViewChart.php?op=activeinactive" target="_blank">Num of Active and Inactive Items</a></li>
	    <li><a href="report/ViewChart.php?op=catvalue" target="_blank">Total Invested Value per Category</a></li>
	    <li><a href="report/ViewChart.php?op=branchvalue" target="_blank">Total Invested Value per Branch</a></li>
	</ol>
</div>
<?php $tmpl->place('footer'); ?>
