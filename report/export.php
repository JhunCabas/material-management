<?php
session_start();
include ('sql.php');
extract($_REQUEST);
//error_reporting(E_ALL);

$header=" ";

switch ($op) {
    case "inventory":
            $name = "InventoryListing";	
            $table = exportInv();
			
            $td = '<td>#</td>
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
                  <td>Status</td>';
            break; 
    
    case "stock":
            $name = "BranchStockListing";
            $table = exportStock();
              $td = '<td>#</td>
                  <td>Branch ID</td>
                  <td>Item ID</td>
                  <td>Quantity</td>';
            break;
            }

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=".$name.".".$type."");
header("Pragma: no-cache");
header("Expires: 0");

// item_code, description, weight, dimension, part_number, unit_of_measure, rate, currency, purchase_year, detailed_description,  status

?> 

<table width="100%" border="2" cellspacing="0" cellpadding="0">

  <tr bordercolor="#000000" align="center" bordercolordark="#000000"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <?php 				  
				  	echo $td;
					  ?>
    </font> </tr>
  <?php 
	  
				  	echo $table;
					  ?>
</table>
