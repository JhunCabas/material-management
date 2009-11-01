<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$pdf=new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','',10);
$pdf->Image('UMW.jpeg',10,3,180,23); 

//draw line
$pdf->Line(10,60,10,180); //outer
$pdf->Line(10,60,205,60);
$pdf->Line(10,180,205,180);
$pdf->Line(205,60,205,180);

$pdf->Line(20,60,20,180); //1
$pdf->Line(40,60,40,180); //2
$pdf->Line(135,60,135,180); //3-qtty
$pdf->Line(150,60,150,180); //4-uom
$pdf->Line(162,60,162,180); //5
$pdf->Line(195,60,195,180); //6

$pdf->Line(10,74,205,74);
//$pdf->Line(10,170,205,170);

//table title
$pdf->Text(12,64,'NO');
$pdf->Text(22,64,'ITEM');
$pdf->Text(22,67,'CODE');
$pdf->Text(42,64,'DESCRIPTION');
$pdf->Text(137,64,'QTY');
$pdf->Text(152,64,'UOM');
$pdf->Text(167,64,'REMARKS');
$pdf->Text(197,64,'(A)');


$starting = 78;
////////////

            $count = '0';
            //SQL  //gives the following  
            //   doc_number, doc_date, doc_type, branch_id, supplier, do_no, po_no, inspector, inspector_date, status, item_id, description, quantity, remark, assessment, 
            $sql ="SELECT A.doc_number, A.doc_date, A.doc_type, A.branch_id, A.supplier, A.do_no, A.po_no, A.inspector, A.inspector_date, A.status,
B.item_id,B.description,B.quantity,B.remark,B.assessment,
C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            E.name as supplierName
            FROM good_receipt_notes A, good_receipt_note_details B, branches C, inv_items D, suppliers E
            WHERE A.doc_number='$GRNnum'
            AND A.branch_id = C.id
            AND A.supplier = E.id
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
            		  
            			
            			$req_date = $row['inspector_date'];
            			
                 
                  $remark = $row['remark'];
            			
      $remarke = str_split($remark, 11);
     
       if (strlen($remark) > 11 )
      {
       $starting = $starting + 5;
      $pdf->Text(165,$starting,$desc[1]);
       }
       
                  $assessment = $row['assessment'];
     
      $desc = str_split($description, 52);
     
     
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(42,$starting,$desc[0]);
      $pdf->Text(137,$starting,$quantity);
      $pdf->Text(152,$starting,$unitmeasure);
      $pdf->Text(165,$starting,$remarke[0]);
      $pdf->Text(197,$starting,$assessment);
      
      
       if (strlen($description) > 52 )
      {
       $starting = $starting + 5;
      $pdf->Text(42,$starting,$desc[1]);
       }
      
    $starting = $starting + 5;
            			
            		$doc_date = $row['doc_date'];
            		$requester = $row['inspector'];
            		$requester_date = $row['inspector_date'];
            			
            			
                  
                  $supplierName =$row['supplierName'];
                  
                  $do_no =$row['do_no'];
                  $po_no =$row['po_no'];
                  
                 // $supplierFax=$row['fax_no'];
                  
                  //$supplierAddress = explode(",", $row['supplierAddress']);
                
                  //Must add Branch Address
                  //$supplierAddress = explode(",", $row['supplierAddress']);
                  
                  
                  
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


//$pdf->Text(125,56,"Tel: ".$branchNo);
//$pdf->Text(155,56,"Fax: ".$branchFax);
//$pdf->Text(125,44,$sad3);
//$pdf->Text(125,40,$sad2);
//$pdf->Text(125,36,$branchLocation);
//$pdf->Text(22,24,$branchName);
//$pdf->Text(12,32,"Ship To: ".$branchName);
$pdf->Text(13,32,"SUPPLIER: ".$supplierName);

$pdf->Text(13,38,"DO NO: ".$do_no);
$pdf->Text(13,45,"PO NO: ".$po_no);


$pdf->Text(130,32,"GOODS RECEIPT NOTE");

$pdf->Text(130,37,"GRN Number: ".$GRNnum);
$pdf->Text(130,42,"Date: ".$date);


$pdf->Text(12,190,"ASSESMENT OF CONDITION (A)");
$pdf->Text(12,195,"OK :Item received in good condition");
$pdf->Text(12,200,"NG :Item is in damaged condition");
$pdf->Text(12,205,"Q  :Quantity supplied did not match DO quantity");
$pdf->Text(12,210,"X  :Item sent is different from PO description");


$pdf->Text(12,230,"Received and Inspected By : ");
$pdf->Text(12,240,"Name : ".$requester);
$pdf->Text(12,244,"Date : ".$requester_date);

$pdf->Text(12,253,"Signature ");


$pdf->Line(10,265,205,265);

$pdf->Text(15,270,$date);
$pdf->Text(170,270,"PAGE 1 OF 1");


$pdf->Output("".$GRNnum.".pdf", "I");
?>
