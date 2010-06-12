<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$pdf=new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','B',10);

//draw line
$pdf->Line(10,60,10,180); //outer
$pdf->Line(10,60,205,60);
$pdf->Line(10,180,205,180);
$pdf->Line(205,60,205,180);

$pdf->Line(20,60,20,170); //1
$pdf->Line(40,60,40,170); //2
$pdf->Line(135,60,135,170); //3-qtty
$pdf->Line(150,60,150,170); //4-uom
$pdf->Line(165,60,165,180); //5
$pdf->Line(185,60,185,180); //6

$pdf->Line(10,70,205,70);
$pdf->Line(10,170,205,170);

//table title
$pdf->Text(12,64,'NO');
$pdf->Text(22,64,'ITEM');
$pdf->Text(22,67,'CODE');
$pdf->Text(42,64,'DESCRIPTION');
$pdf->Text(137,64,'QTTY');
$pdf->Text(152,64,'UOM');
$pdf->Text(167,64,'UNIT');
$pdf->Text(167,67,'PRICE');
$pdf->Text(187,64,'EXT.');
$pdf->Text(187,67,'PRICE');
$pdf->Text(167,175,'TOTAL');
$pdf->Text(100,165,'DISCOUNT');


$starting = 73;
////////////

            $count = '0';
            //SQL  //gives the following  
            //   doc_number, quantity, unit_price, extended_price, item_id, doc_number, branch_id, currency, doc_date, branchLocation, branchNo, branchName, description, unit_of_measure, supplier_1, supplierContact, supplierNum, supplierName, supplierAddress
               
            $sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,B.`description`,
            A.`doc_number`, A.`branch_id`, A.`currency`, date_format(A.doc_date, '%D %M, %Y') as doc_date, A.payment, A.delivery, A.discount, A.total, A.special_instruction,A.requester,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            A.supplier_1, A.supplier_2, A.supplier_3, E.contact_person as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, purchase_details B, branches C, inv_items D, suppliers E
            WHERE A.doc_number='$PRnum'
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
            			
                  $unitprice = sprintf("%.2f",$unitprice);
                  $total = sprintf("%.2f",$total);
                  $extendedprice = sprintf("%.2f",$extendedprice);
                  $discount = sprintf("%.2f",$discount);
            			
     
     
      $desc = str_split($description, 50);
     
     
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(42,$starting,$desc[0]);
      $pdf->Text(137,$starting,$quantity);
      $pdf->Text(152,$starting,$unitmeasure);
      $pdf->Text(167,$starting,$unitprice);
      $pdf->Text(187,$starting,$extendedprice);
      
       if (strlen($description) > 50 )
      {
      
      $pdf->Text(42,$starting+3,$desc[1]);
       }
      
    $starting = $starting + 5;
            			
            		$doc_date = $row['doc_date'];
            		$requester = $row['requester'];
            			
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


  
  $sql2 ="SELECT  E.name
            FROM purchases A, suppliers E
            WHERE A.doc_number='$PRnum'
            AND A.supplier_2 = E.id";
                 
             connectToDB();
                     $getSup2 = mysql_query($sql2) or die ("Execution SQL; error");
            $sup2 = mysql_fetch_array($getSup2);
            $sup2name = $sup2['name'];
            				
 $sql3 ="SELECT  E.name
            FROM purchases A, suppliers E
            WHERE A.doc_number='$PRnum'
            AND A.supplier_3 = E.id";
                 
             connectToDB();
                     $getSup3 = mysql_query($sql3) or die ("Execution SQL; error");
            $sup3 = mysql_fetch_array($getSup3);
            $sup3name = $sup3['name'];
  
    
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
           // $pdf->Text(22,48,$supp_add3);
           // $pdf->Text(22,44,$supp_add2);
           // $pdf->Text(22,40,$supp_add1);
            $pdf->Text(22,33,"Recommended Supplier : ".$supplierName);
            //$pdf->Text(22,32,$supplierContact);
            //$pdf->Text(12,32,"To: ");
            
            
           $pdf->Text(22,37," / Supplier 2: ".$sup2name);
           $pdf->Text(22,41," / Supplier 3: ".$sup3name);
           //$pdf->Text(22,40,$supp_add1);

$pdf->Text(125,56,"Tel: ".$branchNo);
//$pdf->Text(155,56,"Fax: ".$branchFax);
//$pdf->Text(125,44,$sad3);
//$pdf->Text(125,40,$sad2);
//$pdf->Text(125,36,$branchLocation);
$pdf->Text(22,24,$branchName);
$pdf->Text(22,20,"Ship To:");

$pdf->Text(130,20,"Date: ".$date);
$pdf->Text(130,24,"Purchase Request No: ".$PRnum);

$pdf->Text(12,190,"SPECIAL INSTRUCTIONS AND TERMS");
$pdf->Text(12,193,$special_instruction);
$pdf->Text(160,190,"PAGE 1 OF 1");
$pdf->Text(12,203,"Delivery Terms:");
$pdf->Text(12,205,"-----------------------");
$pdf->Text(12,209,$delivery);
$pdf->Text(130,203,"Payment Terms:");
$pdf->Text(130,205,"-----------------------");
$pdf->Text(130,209,$payment);
$pdf->Text(12,230,"Prepared By : ".$requester);
$pdf->Text(12,237,"Endorsed By : Karthigeyan Nallasamy / Chew Kwong Chee");
$pdf->Text(12,244,"Approved By : Pendin Saragih");


$pdf->Text(187,175,$total);

$pdf->Text(187,165,"($discount)");

$pdf->Output("".$PRnum.".pdf", "I");
?>
