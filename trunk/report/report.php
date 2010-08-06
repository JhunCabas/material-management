<?php
session_start();
include ('sql.php');
extract($_REQUEST);
extract($_GET);
//error_reporting(E_ALL);

$header=" ";
$today = date("dMy");



header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$today-$op-report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// item_code, description, weight, dimension, part_number, unit_of_measure, rate, currency, purchase_year, detailed_description,  status
if ( $op == 'MTF')
{
$getTable = exportMTFTrack($month,$year);
$table = "  <td>#</td>
            <td bgcolor=blue>MTF Number</td>
            <td bgcolor=blue>Date</td>
            <td bgcolor=blue>Requester</td>
            <td bgcolor=blue>Approver</td>
            <td bgcolor=blue>Branch From</td>
            <td bgcolor=blue>Branch To</td>
            <td bgcolor=blue>Status</td>";
}else if ( $op == 'PI')
{
$getTable = exportPITrack($month,$year);
$table = "  <td>#</td>
            <td bgcolor=blue>PI Number</td>
            <td bgcolor=blue>Date</td>
            <td bgcolor=blue>Issuer</td>
            <td bgcolor=blue>Branch From</td>
            <td bgcolor=blue>Status</td>";
}else if ( $op == 'Sup')
{
$getTable = getSupplierList();
$table = "  <td>#</td>
            <td bgcolor=blue>Supplier Name</td>
            <td bgcolor=blue>Location</td>
            <td bgcolor=blue>Address</td>
            <td bgcolor=blue>Contact Person</td>
            <td bgcolor=blue>Contact</td>
            <td bgcolor=blue>Info</td>
            <td bgcolor=blue>Fax No</td>
            <td bgcolor=blue>Status</td>";
}else if ( $op == 'SupPur')
{
$getTable = getSupplierPurchaseList($month,$year);
$table = "  
             <td bgcolor=blue>PO Number</td>
            <td bgcolor=blue>Supplier Name</td>
            <td bgcolor=blue>Number of PO</td>
            <td bgcolor=blue>Total Value (RM)</td>";
}


   

?> 

<pr/><table width="100%" border="2" cellspacing="0" cellpadding="0">

  <tr bordercolor="#000000" align="center" bordercolordark="#000000"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <?=$table;?>
    </font> </tr>
    
              
  <?php 
			 
			// $getTracker = exportTracker($month,$year);
				  
				  	echo $getTable;
					
          echo "<p></p>This report is update till $today<p/><br/>";
            ?>
</table>