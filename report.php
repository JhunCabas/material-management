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
	<h2>User Report</h2><h3>MTF / PI Reports</h3>
	<ol>
	
          	<?php
        // Get the month and year of the current month
        list($m, $y) = explode('-', date('m-Y'));
        $m = date('m');
        $y = date('Y');
        ////////////////////////
        for ($i = -3; $i < 0; $i++) {
          $ts = mktime(0,0,0,(date('m') + $i),1);
          $mth = date('m', $ts);
          $yr  = date('Y', $ts);
         echo "
         	<li><a href='report/report.php?op=MTF&month=$mth&year=$yr' target='_blank'>MTF Report $mth/$yr</a></li>
         "; // print out your option
        }
        ?>
	
	     <?php
        // Get the month and year of the current month
        list($m, $y) = explode('-', date('m-Y'));
        $m = date('m');
        $y = date('Y');
        ////////////////////////
        for ($i = -3; $i < 0; $i++) {
          $ts = mktime(0,0,0,(date('m') + $i),1);
          $mth = date('m', $ts);
          $yr  = date('Y', $ts);
         echo "
        	    <li><a href='report/report.php?op=PI&month=$mth&year=$yr' target='_blank'>PI Report $mth/$yr</a></li>
         "; // print out your option
        }
        ?>
 
	</ol>
	
	</ol>
	
	<h3>Total Purchase</h3>
	
          	    <?php
        // Get the month and year of the current month
        list($m, $y) = explode('-', date('m-Y'));
        $m = date('m');
        $y = date('Y');
        ////////////////////////
        for ($i = -3; $i < 0; $i++) {
          $ts = mktime(0,0,0,(date('m') + $i),1);
          $mth = date('m', $ts);
          $yr  = date('Y', $ts);
         echo "
        	    <li><a href='report/POReport.php?op=view&month=$mth&year=$yr' target='_blank'>Purchase Order for $mth/$yr</a></li>
         "; // print out your option
        }
        ?>
	<ol>
  	       <?php
          // Get the month and year of the current month
          list($m, $y) = explode('-', date('m-Y'));
          $m = date('m');
          $y = date('Y');
          ////////////////////////
          for ($i = -3; $i < 0; $i++) {
            $ts = mktime(0,0,0,(date('m') + $i),1);
            $mth = date('m', $ts);
            $yr  = date('Y', $ts);
           /*echo "
          	    <li><a href='report/report.php?op=PO&month=$mth&year=$yr' target='_blank'>Purchase Order Report $mth/$yr</a></li>
           "; // print out your option
          */}
          ?>
  	</ol>
	
	
		<h3>PR-PO-GRN Tracker</h3>
	<ol>
	
	<?php
// Get the month and year of the current month
list($m, $y) = explode('-', date('m-Y'));
$m = date('m');
$y = date('Y');

// if you're wanting the previous three months, set up a loop to start with
// three months ago (-3) and to up through last month (-1). If you want to 
// include the current month, simply change the "$i < 0" to "$i < 1"
for ($i = -6; $i < 0; $i++) {
  $ts = mktime(0,0,0,(date('m') + $i),1);
  $mth = date('m', $ts);
  $yr  = date('Y', $ts);
 echo "<li><a href='report/tracker.php?month=$mth&year=$yr' target='_blank'>PR-PO-GRN Tracker $mth/$yr</a></li>"; // print out your option
}

?>
	
	
	</ol>
	
	<h3>MOF Report Tracker</h3>
	<ol>
	
	<?php
// Get the month and year of the current month
list($m, $y) = explode('-', date('m-Y'));
$m = date('m');
$y = date('Y');

// if you're wanting the previous three months, set up a loop to start with
// three months ago (-3) and to up through last month (-1). If you want to 
// include the current month, simply change the "$i < 0" to "$i < 1"
for ($i = -6; $i < 0; $i++) {
  $ts = mktime(0,0,0,(date('m') + $i),1);
  $mth = date('m', $ts);
  $yr  = date('Y', $ts);
 echo "<li><a href='report/tracker.php?op=MOF&month=$mth&year=$yr' target='_blank'>MOF Report Tracker $mth/$yr</a></li>"; // print out your option
}

?>
	
	
	</ol>
  
  
  <h3>Other Reports</h3>
	<ol>
		<li><a href="report/ViewChart.php?op=itempurchaseyear" target="_blank">Num of Inventory Items vs Purchased Year</a></li>
	    <li><a href="report/ViewChart.php?op=activeinactive" target="_blank">Num of Active and Inactive Items</a></li>
	    <li><a href="report/ViewChart.php?op=catvalue" target="_blank">Total Invested Value per Category</a></li>
	    <li><a href="report/ViewChart.php?op=branchvalue" target="_blank">Total Invested Value per Branch</a></li>
	</ol>
	
</div>
<?php $tmpl->place('footer'); ?>
