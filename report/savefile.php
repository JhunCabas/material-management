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
   
$sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,B.`description`,
            A.`po_number`, A.`branch_id`, A.`currency`, date_format(A.po_date, '%D %M, %Y') as doc_date, A.payment, A.delivery, A.discount, A.total, A.special_instruction,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,F.country as currency,
            A.supplier_1, E.contact_person as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, purchase_details B, branches C, inv_items D, suppliers E,currencies F
            WHERE A.doc_number='$POnum'
            AND B.doc_number = A.doc_number
            AND A.branch_id = C.id
            AND A.supplier_1 = E.id
            AND B.item_id = D.id
            AND A.currency = F.id";
     
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
<table width="800" height="100%" border="0">
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
    <td height="" valign="top"><strong>To :</strong></td>
    <td width="51%" align="left" valign="top">
    <?php
    echo $supplierContact."<br/>". $supplierName."<br/>".$supplierAddress."<br/> $supplierNum"; 
    ?>    </td>
    <td width="" valign="top"><p><strong>Ship To </strong>:</p>    </td>
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
<table width="700" height="" border="1" bordercolor="#000000">
  <tr>
    <td width="23" height="59">No</td>
    <td width="55">ITEM CODE</td>
    <td width="273">DESCRIPTION</td>
    <td width="55">QTY</td>
    <td width="41">UOM</td>
    <td width="71">UNIT PRICE</td>
    <td width="146">EXTENDED PRICE</td>
  </tr>
  <tr>
    <td height="" colspan="7">
    
    <table width="700" height="" border="0">
  <tr>
    <td width="5%" align="center" valign="top" ><?php echo $numbering; ?></td>
    <td width="8%" align="center" valign="top"><?php echo $item_ids; ?></td>
    <td width="40%" valign="top"><?php echo $descriptions; ?></td>
    <td width="8%" align="center" valign="top"><?php echo $quantitys; ?></td>
    <td width="7%" align="center" valign="top"><?php echo $unitmeasures; ?></td>
    <td width="11%" align="right" valign="top"><?php echo $unitprices; ?></td>
    <td width="21%" align="right" valign="top"><?php echo $extendedprices; ?></td>
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
<p><u>Payment Term :</u><br>
  <?php echo $payment; ?></p>
<p><u>Deliver Date :</u><br>
<?php echo $delivery; ?></p>
</body>
</html>
