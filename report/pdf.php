<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$pdf=new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','B',8);

//draw line
$pdf->Line(10,60,10,170); //outer
$pdf->Line(10,60,205,60);
$pdf->Line(10,170,205,170);
$pdf->Line(205,60,205,170);

$pdf->Line(20,60,20,160); //1
$pdf->Line(40,60,40,160); //2
$pdf->Line(125,60,125,160); //3
$pdf->Line(145,60,145,160); //4
$pdf->Line(165,60,165,170); //5
$pdf->Line(185,60,185,170); //6

$pdf->Line(10,70,205,70);
$pdf->Line(10,160,205,160);

//table title
$pdf->Text(12,63,'NO');
$pdf->Text(22,63,'ITEM CODE');
$pdf->Text(42,63,'DESCRIPTION');
$pdf->Text(127,63,'QUANTITY');
$pdf->Text(147,63,'UOM');
$pdf->Text(167,63,'UNIT PRICE');
$pdf->Text(187,63,'EXTENDED');
$pdf->Text(187,66,'PRICE');
$pdf->Text(167,165,'TOTAL');
$pdf->Text(100,155,'DISCOUNT');


$starting = 73;
////////////

            $count = '0';
            //SQL  //gives the following  
            //   doc_number, quantity, unit_price, extended_price, item_id, doc_number, branch_id, currency, doc_date, branchLocation, branchNo, branchName, description, unit_of_measure, supplier_1, supplierContact, supplierNum, supplierName, supplierAddress
               
            $sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,
            A.`doc_number`, A.`branch_id`, A.`currency`, A.`doc_date`, A.payment, A.delivery, A.discount, A.total, A.special_instruction,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`description`, D.`unit_of_measure`,
            A.supplier_1, A.supplier_1_contact as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, purchase_details B, branches C, inv_items D, suppliers E
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
            			 
            			$item_id = $row['item_id'];
            			$description = $row['description'];
            			$quantity = $row['quantity'];
            			$unitmeasure = $row['unit_of_measure'];
            			$unitprice = $row['unit_price'];
            		  $extendedprice = $row['extended_price'];
            		  
            			$total = $row['total'];
            			$discount = $row['discount'];
            			
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(42,$starting,$description);
      $pdf->Text(127,$starting,$quantity);
      $pdf->Text(147,$starting,$unitmeasure);
      $pdf->Text(167,$starting,$unitprice);
      $pdf->Text(187,$starting,$extendedprice);
      
    $starting = $starting + 5;
            			
            		$doc_date = $row['doc_date'];
            			
            			$branchName =$row['branchName'];
                  $branchLocation = $row['branchLocation'];
                  //must add branchFax
                  $branchNo=$row['branchNo'];
                  
                  $supplierName =$row['supplierName'];
                  $supplierContact =$row['supplierContact'];
                  $supplierAddress = $row['supplierAddress'];
                  $supplierNum=$row['supplierNum'];
                  $supplierFax=$row['fax_no'];
                  
                  //$supplierAddress = explode(",", $row['supplierAddress']);
                  
            		  $supp_add1 = $row['add1'];
            		  $supp_add2 = $row['add2'];
            		  $supp_add3 = $row['add3'];
                  //Must add Branch Address
                  //$supplierAddress = explode(",", $row['supplierAddress']);
                  
                  $payment = $row['payment'];
                  $delivery = $row['delivery'];
                  $special_instruction = $row['special_instruction'];
                  
                  			}

////////////


//this is sample data, change this code and replace the data from mysql
/*
$no2 = "2";
$description2 = "Ford Focus 2.0";
$qty2 = "1";
$uom2 = "set";
$unitprice2 = "35000";
$price2 = "35000";
/////////////////////////////////////////end

$starting = $starting + 5;
$pdf->Text(12,$starting,$no2);
$pdf->Text(22,$starting,"BR003");
$pdf->Text(42,$starting,$description2);
$pdf->Text(127,$starting,$qty2);
$pdf->Text(147,$starting,"set");
$pdf->Text(167,$starting,$unitprice2);
$pdf->Text(187,$starting,$price2);


$to = "Mohd Zakwan Nawawi";
$ad1= "No 70 C Kg Gemia";
$ad2= "23100 Paka";
$ad3= "Dungun, Terengganu";
$phone = "013-2935699";
$fax = "09-8273452";

$sto = "Mahen";
$sad1= "No 70 C Kg Gemia";
$sad2= "23100 Paka";
$sad3= "Dungun, Terengganu";
$sphone = "013-2935699";
$sfax = "09-8273452";

*/
$date = $doc_date;


            $pdf->Text(22,56,"Tel: ".$supplierNum);
            $pdf->Text(52,56,"Fax: ".$supplierFax);
            $pdf->Text(22,48,$supp_add3);
            $pdf->Text(22,44,$supp_add2);
            $pdf->Text(22,40,$supp_add1);
            $pdf->Text(22,36,$supplierName);
            $pdf->Text(22,32,$supplierContact);
            $pdf->Text(12,32,"To: ");

$pdf->Text(125,56,"Tel: ".$branchNo);
//$pdf->Text(155,56,"Fax: ".$branchFax);
$pdf->Text(125,44,$sad3);
$pdf->Text(125,40,$sad2);
$pdf->Text(125,36,$branchLocation);
$pdf->Text(125,32,$branchName);
$pdf->Text(110,32,"Ship To:");

$pdf->Text(130,20,"Date: ".$date);
$pdf->Text(130,24,"Purchase Order No: ".$POnum);

$pdf->Text(12,180,"SPECIAL INSTRUCTIONS AND TERMS");
$pdf->Text(12,183,$special_instruction);
$pdf->Text(160,180,"PAGE 1 OF 1");
$pdf->Text(12,193,"Delivery Terms:");
$pdf->Text(12,195,"-----------------------");
$pdf->Text(12,199,$delivery);
$pdf->Text(130,193,"Payment Terms:");
$pdf->Text(130,195,"-----------------------");
$pdf->Text(130,199,$payment);


$pdf->Text(187,165,$total);

$pdf->Text(187,155,"($discount)");

$pdf->Output("".$POnum.".pdf", "I");
?>