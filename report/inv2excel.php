<?php
session_start();
include ('sql.php');
extract($_REQUEST);
//error_reporting(E_ALL);

$header=" ";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=UPSB-InventoryListing.xls");
header("Pragma: no-cache");
header("Expires: 0");

// item_code, description, weight, dimension, part_number, unit_of_measure, rate, currency, purchase_year, detailed_description,  status

?> 

<table width="100%" border="2" cellspacing="0" cellpadding="0">

  <tr bordercolor="#000000" align="center" bordercolordark="#000000"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <td>#</td>
    <td>Item Code</td>
    <td>Description</td>
    <td>Weight</td>
    <td>Dimension</td>
    <td>Part Number</td>
    <td>UOM</td>
    <td>Rate</td>
    <td>Currency</td>
    <td>Purchase Year</td>
    <td>Detailed Description</td>
    <td>Status</td>
    </font> </tr>
  <?php 
			 
			 $getInv = exportInv($pid);
				  
				  	echo $getInv;
					  ?>
</table>
