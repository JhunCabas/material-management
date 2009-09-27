<?php

extract($_GET);
//include('../config.php');
include_once('class/DBConn.php');
$title = $POnum;
$count = '0';

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Number-".$POnum.".doc");
header("Pragma: no-cache");
header("Expires: 0");


//SQL  //gives the following  
//   doc_number, quantity, unit_price, extended_price, item_id, doc_number, branch_id, currency, doc_date, branchLocation, branchNo, branchName, description, unit_of_measure, supplier_1, supplierContact, supplierNum, supplierName, supplierAddress
   
$sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,
A.`doc_number`, A.`branch_id`, A.`currency`, A.`doc_date`, A.payment, A.delivery,
C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
D.`description`, D.`unit_of_measure`,
A.supplier_1, A.supplier_1_contact as supplierContact, E.contact as supplierNum, E.name as supplierName, E.address as supplierAddress
FROM umw_mms.purchases A, umw_mms.purchase_details B, umw_mms.branches C, umw_mms.inv_items D, umw_mms.suppliers E
WHERE A.doc_number='$POnum'
AND B.doc_number = A.doc_number
AND A.branch_id = C.id
AND A.supplier_1 = E.id
AND B.item_id = D.id";
     
 connectToDB();
         $getData = mysql_query($sql) or die ("Execution SQL; error");
while ( $row = mysql_fetch_array($getData))
					{
			$count++;
			$item_ids .= $row['item_id']."<br/><br/>";
			$descriptions .= $row['description']."<p>";
			$quantitys .= $row['quantity']."<br/><br/>";
			$unitmeasures .= $row['unit_of_measure']."<br/><br/>";
			$unitprices .= $row['unit_price']."<br/><br/>";
			$extendedprices .= $row['extended_price']."<br/><br/>";
			$numbering .="$count<br/><br/>";
			
			$total += $row['extended_price'];
			
			$doc_date = $row['doc_date'];
			
			$branchName =$row['branchName'];
      $branchLocation = $row['branchLocation'];
      $branchNo=$row['branchNo'];
      
      $supplierName =$row['supplierName'];
      $supplierContact =$row['supplierContact'];
      $supplierAddress = $row['supplierAddress'];
      $supplierNum=$row['supplierNum'];
      
      $payment = $row['payment'];
      $delivery = $row['delivery'];
      
      			}
  //id, doc_number, item_id, quantity, unit_price, extended_price 
  
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
</head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="700" height="150" border="0">
  <tr>
    <td width="5%" rowspan="2">&nbsp;</td>
    <td colspan="2" rowspan="2">&nbsp;</td>
    <td width="14%">Date</td>
    <td width="21%"><?php echo $doc_date; ?></td>
  </tr>
  <tr>
    <td>Purchase Order </td>
    <td><?php echo $POnum; ?></td>
  </tr>
  <tr>
    <td height="101" valign="top"><strong>To :</strong></td>
    <td width="51%" align="left" valign="top">
    <?php
    echo $supplierContact."<br/>". $supplierName."<br/>".$supplierAddress."<br/> $supplierNum"; 
    ?>    </td>
    <td width="9%" valign="top"><p><strong>Ship To </strong>:</p>    </td>
    <td colspan="2" align="left" valign="top"><p>
    <?php 
    echo $branchName."<br/>". $branchLocation."<br/>".$branchNo ; 
    ?>
    </p>
    </td>
  </tr>
  <tr>
    <td height="27" colspan="8">&nbsp;</td>
  </tr>
</table>
<br/>
<table width="700" height="" border="1">
  <tr>
    <td width="5" height="59">No</td>
    <td width="10%">ITEM CODE</td>
    <td width="35%">DESCRIPTION</td>
    <td width="10%">QTY</td>
    <td width="10%">UOM</td>
    <td width="15%">UNIT PRICE</td>
    <td width="15%">EXTENDED PRICE</td>
  </tr>
  <tr>
    <td height="" colspan="7">
    
    <table width="700" height="" border="0">
  <tr>
    <td width="5%" align="center" valign="top" ><?php echo $numbering; ?></td>
    <td width="10%" align="center" valign="top"><?php echo $item_ids; ?></td>
    <td width="35%" valign="top"><?php echo $descriptions; ?></td>
    <td width="10%" align="center" valign="top"><?php echo $quantitys; ?></td>
    <td width="10%" align="center" valign="top"><?php echo $unitmeasures; ?></td>
    <td width="15%" align="right" valign="top"><?php echo $unitprices; ?></td>
    <td width="15%" align="right" valign="top"><?php echo $extendedprices; ?></td>
  </tr>
	</table>    </td>
  </tr>

  <tr>
    <td height="30" colspan="4">&nbsp;</td>
    <td colspan="2"><strong>TOTAL</strong></td>
    <td align="right"><p><?php echo $total; ?></p></td>
  </tr>
</table>
<p><u>Special Instructions and Terms</u></p>
<p>Internal Ref : </p>
<p><u>Payment Term :</u></p>
<br><?php echo $payment; ?></br>
<p>Deliver Date : </p>
<br><?php echo $delivery; ?></br>
</body>
</html>
