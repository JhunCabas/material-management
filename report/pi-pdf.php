<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$pdf=new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','',10);
$pdf->Image('UMW.jpeg',10,3,180,23); 

//draw line
$pdf->Line(10,50,10,220); //outer
$pdf->Line(10,50,205,50);
$pdf->Line(10,220,205,220);
$pdf->Line(205,50,205,220);

$pdf->Line(20,50,20,220); //1
$pdf->Line(44,50,44,220); //2
$pdf->Line(135,50,135,220); //3-qtty
$pdf->Line(150,50,150,220); //4-uom
$pdf->Line(164,50,164,220); //5

$pdf->Line(10,57,205,57);
//$pdf->Line(10,170,205,170);

//table title
$pdf->Text(12,54,'NO');
$pdf->Text(22,54,'ITEM CODE');
$pdf->Text(52,54,'DESCRIPTION');
$pdf->Text(138,54,'QTY');
$pdf->Text(153,54,'UOM');
$pdf->Text(170,54,'REMARKS');
//$pdf->Text(197,64,'(A)');


$starting = 63;
////////////

            $count = '0';
            //SQL  //gives the following  
            // doc_number, doc_date, doc_type, branch_id, notes, issuer, issuer_date, 
            // receiver, receiver_date, item_id, quantity, remark, status, description  
            $sql ="SELECT A.doc_number,  date_format(A.doc_date, '%D %M, %Y') as doc_date, A.doc_type, A.branch_id, A.notes, A.issuer, A.issuer_date, A.receiver, A.receiver_date,
        B.item_id, B.quantity, B.remark, B.status,
        D.description,  D.unit_of_measure
            FROM production_issues A, production_issue_details B, branches C, inv_items D
            WHERE A.doc_number='$PInum'
            AND A.doc_number = B.doc_number
            AND A.branch_id = C.id
            AND B.item_id = D.id;";
                 
             connectToDB();
                     $getData = mysql_query($sql) or die ("Execution SQL; error");
            while ( $row = mysql_fetch_array($getData))
            					{
            			 $count++;
            			 
            			$item_id = $row['item_id'];
            			$description = $row['description'];
                  $description = html_entity_decode($description);
            			$quantity = $row['quantity'];
            			$unitmeasure = $row['unit_of_measure'];
            		  
            			
            			$issuer = $row['issuer'];
            			$req_date = $row['issuer_date'];
            			
                 
                  $remark = $row['remark'];
            			
      $remarke = str_split($remark, 19);
     
       if (strlen($remark) > 19 )
      {
       $starting = $starting + 5;
      $pdf->Text(166,$starting,$remarke[1]);
       }
       
                 
      $desc = str_split($description, 52);
     
     
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(46,$starting,$desc[0]);
      $pdf->Text(138,$starting,$quantity);
      $pdf->Text(153,$starting,$unitmeasure);
      $pdf->Text(166,$starting,$remarke[0]);
      
      
       if (strlen($description) > 52 )
      {
       $starting = $starting + 5;
      $pdf->Text(46,$starting,$desc[1]);
       }
      
    $starting = $starting + 5;
            			
            		$doc_date = $row['doc_date'];
            		//$requester = $row['inspector'];
            		//$requester_date = $row['inspector_date'];
            			
            			
               
                  
                  			}

////////////


  



$date = $doc_date;


//$pdf->Text(125,56,"Tel: ".$branchNo);
//$pdf->Text(155,56,"Fax: ".$branchFax);
//$pdf->Text(125,44,$sad3);
//$pdf->Text(125,40,$sad2);
//$pdf->Text(125,36,$branchLocation);
//$pdf->Text(22,24,$branchName);
//$pdf->Text(12,32,"Ship To: ".$branchName);
//$pdf->Text(13,32,"SUPPLIER: ".$supplierName);


$pdf->SetFont('Arial','B',11);
$pdf->Text(130,32,"PRODUCTION ISSUE FORM");


$pdf->SetFont('Arial','',10);
$pdf->Text(130,37,"PI Number: ".$PInum);
$pdf->Text(130,42,"Date: ".$date);



$pdf->Text(12,235,"Issued  By : ");
$pdf->Text(12,240,"Name : ".$issuer);
$pdf->Text(12,244,"Date : ".$req_date);
$pdf->Text(12,253,"Signature ");

$pdf->Text(130,235,"Received  By : ");
$pdf->Text(130,240,"Name : ".$issuer);
$pdf->Text(130,244,"Date : ".$req_date);
$pdf->Text(130,253,"Signature ");


$pdf->Line(10,265,205,265);

$pdf->Text(15,270,$date);
$pdf->Text(170,270,"PAGE 1 OF 1");


$pdf->Output("".$PInum.".pdf", "I");
?>
