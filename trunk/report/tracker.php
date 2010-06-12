<?php
session_start();
include ('sql.php');
extract($_REQUEST);
extract($_GET);
//error_reporting(E_ALL);

$header=" ";
$today = date("dMy");

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$op-$today-MonthTracker.xls");
header("Pragma: no-cache");
header("Expires: 0");

// item_code, description, weight, dimension, part_number, unit_of_measure, rate, currency, purchase_year, detailed_description,  status

?> 

<table width="100%" border="2" cellspacing="0" cellpadding="0">

  <tr bordercolor="#000000" align="center" bordercolordark="#000000"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <td>#</td>
    <? if ( $op == 'MOF')
      { echo "<td>MOF Number</td>";
      }
    ?>
    <td>PR Number</td>
    <td>PR Date</td>
    <td>PO Number</td>
    <td>PO Date</td>
    <td>GRN Number</td>
    <td>GRN Date</td>
    <td>Status</td>
    </font> </tr>
  <?php 
			if ( $op == 'MOF')
      { $getTracker = exportMOFTracker($month,$year);
      }
			
      else $getTracker = exportTracker($month,$year);
				  
				  	echo $getTracker;
					
          echo "<p></p><p></p><br/>This report is update till $today";
            ?>
</table>