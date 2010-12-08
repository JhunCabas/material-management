<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$doc_num = $GRNnum;

////////////
$perpage = 13; 
$limit = $perpage;
$page = 1;

$count ="SELECT DISTINCT COUNT(B.`doc_number`) AS count
            FROM good_receipt_notes A, good_receipt_note_details B
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number;";
       connectToDB();
                     $getCount = mysql_query($count) or die ("Execution SQL; error");
             $rowCount = mysql_fetch_array($getCount);
            				
            	$items = $rowCount['count'];
               $items = $items/$perpage; 
               $totalpage = ceil($items);

//////////////////////
/////////////////////
$pdf=new FPDF();
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','',10);
$pdf->Image('UMW.jpeg',10,3,180,23); 

//draw line
$pdf->Line(10,50,10,190); //outer
$pdf->Line(10,50,205,50);
$pdf->Line(10,190,205,190);
$pdf->Line(205,50,205,190);

$pdf->Line(20,50,20,190); //1
$pdf->Line(40,50,40,190); //2
$pdf->Line(135,50,135,190); //3-qtty
$pdf->Line(150,50,150,190); //4-uom
$pdf->Line(162,50,162,190); //5
$pdf->Line(195,50,195,190); //6

$pdf->Line(10,58,205,58);
//$pdf->Line(10,170,205,170);

//table title
$pdf->Text(12,54,'NO');
$pdf->Text(22,54,'ITEM');
$pdf->Text(22,57,'CODE');
$pdf->Text(42,54,'DESCRIPTION');
$pdf->Text(137,54,'QTY');
$pdf->Text(152,54,'UOM');
$pdf->Text(167,54,'REMARKS');
$pdf->Text(197,54,'(A)');


$starting = 63;
////////////

            $count = '0';
            //SQL  //gives the following  
            //   doc_number, doc_date, doc_type, branch_id, supplier, do_no, po_no, inspector, inspector_date, status, item_id, description, quantity, remark, assessment, 
            $sql ="SELECT A.doc_number,  date_format(A.doc_date, '%D %M, %Y') as doc_date, A.doc_type, A.branch_id, A.supplier, A.do_no, A.po_no, A.inspector, A.inspector_date, A.status,
B.item_id,B.description,B.quantity,B.remark,B.assessment,
C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            E.name as supplierName
            FROM good_receipt_notes A, good_receipt_note_details B, branches C, inv_items D, suppliers E
            WHERE A.doc_number='$GRNnum'
            AND A.doc_number = B.doc_number
            AND A.branch_id = C.id
            AND A.supplier = E.id
            AND B.item_id = D.id
             LIMIT 0,$perpage;";
                 
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
            		  
            			
            			$req_date = $row['inspector_date'];
            			
                 
                  $remark = $row['remark'];
            			
      $remarke = str_split($remark, 15);
     
     $anotherRemarkLine = 0;
     
       if (strlen($remark) > 15 )
      {
       $starting2 = $starting + 5;
       $anotherRemarkLine = 5;
      $pdf->Text(165,$starting2,$remarke[1]);
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
      
    $starting = $starting + 5 + $anotherRemarkLine;
            			
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


$pdf->SetFont('Arial','B',11);
$pdf->Text(130,32,"GOODS RECEIPT NOTE");


$pdf->SetFont('Arial','',10);
$pdf->Text(130,37,"GRN Number: ".$GRNnum);
$pdf->Text(130,42,"Date: ".$date);


$pdf->Text(12,195,"ASSESMENT OF CONDITION (A)");
$pdf->Text(12,200,"OK :Item received in good condition");
$pdf->Text(12,205,"NG :Item is in damaged condition");
$pdf->Text(12,210,"Q  :Quantity supplied did not match DO quantity");
$pdf->Text(12,215,"X  :Item sent is different from PO description");


$pdf->Text(12,230,"Received and Inspected By : ");
$pdf->Text(12,240,"Name : ".$requester);
$pdf->Text(12,244,"Date : ".$doc_date);

$pdf->Text(12,253,"Signature ");


$pdf->Line(10,265,205,265);

$pdf->Text(15,270,$date);
$pdf->Text(170,270,"PAGE ".$page." OF ".$totalpage);




////////////                                          ////////////
///do this if more than 15 items.///do this if more than 15 items.
///do this if more than 15 items.///do this if more than 15 items.
///do this if more than 15 items.///do this if more than 15 items.
///do this if more than 15 items.///do this if more than 15 items.
///do this if more than 15 items.///do this if more than 15 items.
///do this if more than 15 items.///do this if more than 15 items.
////////////                                          ////////////

   // $start=$limit;  //in case the limit we tuka, new page start item from previouss
    
if ($items > 1){


        for ($i = 1; $i < $items; $i++) 
            {
            
          $start=$start+$perpage;  //in case the limit we tuka, new page start item from previouss
                
      $page += 1;
    //set start ,limit
    //$start=$start+$perpage; //this created the wrong sql - 5th Jan 2010
       
$pdf->AddPage('P','Letter');
$pdf->SetFont('Arial','',10);
$pdf->Image('UMW.jpeg',10,3,180,23); 

//draw line
$pdf->Line(10,50,10,190); //outer
$pdf->Line(10,50,205,50);
$pdf->Line(10,190,205,190);
$pdf->Line(205,50,205,190);

$pdf->Line(20,50,20,190); //1
$pdf->Line(40,50,40,190); //2
$pdf->Line(135,50,135,190); //3-qtty
$pdf->Line(150,50,150,190); //4-uom
$pdf->Line(162,50,162,190); //5
$pdf->Line(195,50,195,190); //6

$pdf->Line(10,58,205,58);
//$pdf->Line(10,170,205,170);

//table title
$pdf->Text(12,54,'NO');
$pdf->Text(22,54,'ITEM');
$pdf->Text(22,57,'CODE');
$pdf->Text(42,54,'DESCRIPTION');
$pdf->Text(137,54,'QTY');
$pdf->Text(152,54,'UOM');
$pdf->Text(167,54,'REMARKS');
$pdf->Text(197,54,'(A)');


$starting = 63;
////////////

            //SQL  //gives the following  
            //   doc_number, doc_date, doc_type, branch_id, supplier, do_no, po_no, inspector, inspector_date, status, item_id, description, quantity, remark, assessment, 
            $sql ="SELECT A.doc_number,  date_format(A.doc_date, '%D %M, %Y') as doc_date, A.doc_type, A.branch_id, A.supplier, A.do_no, A.po_no, A.inspector, A.inspector_date, A.status,
B.item_id,B.description,B.quantity,B.remark,B.assessment,
C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            E.name as supplierName
            FROM good_receipt_notes A, good_receipt_note_details B, branches C, inv_items D, suppliers E
            WHERE A.doc_number='$GRNnum'
            AND A.doc_number = B.doc_number
            AND A.branch_id = C.id
            AND A.supplier = E.id
            AND B.item_id = D.id
            LIMIT $start,$perpage;";
                 
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
            		  
            			
            			$req_date = $row['inspector_date'];
            			
                 
                  $remark = $row['remark'];
            			
      $remarke = str_split($remark, 15);
     
     $anotherRemarkLine = 0;
     
       if (strlen($remark) > 15 )
      {
       $starting2 = $starting + 5;
       $anotherRemarkLine = 5;
      $pdf->Text(165,$starting2,$remarke[1]);
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
      
    $starting = $starting + 5 + $anotherRemarkLine;
            			
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


$pdf->SetFont('Arial','B',11);
$pdf->Text(130,32,"GOODS RECEIPT NOTE");


$pdf->SetFont('Arial','',10);
$pdf->Text(130,37,"GRN Number: ".$GRNnum);
$pdf->Text(130,42,"Date: ".$date);


$pdf->Text(12,195,"ASSESMENT OF CONDITION (A)");
$pdf->Text(12,200,"OK :Item received in good condition");
$pdf->Text(12,205,"NG :Item is in damaged condition");
$pdf->Text(12,210,"Q  :Quantity supplied did not match DO quantity");
$pdf->Text(12,215,"X  :Item sent is different from PO description");


$pdf->Text(12,230,"Received and Inspected By : ");
$pdf->Text(12,240,"Name : ".$requester);
$pdf->Text(12,244,"Date : ".$doc_date);

$pdf->Text(12,253,"Signature ");


$pdf->Line(10,265,205,265);

$pdf->Text(15,270,$date);
$pdf->Text(170,270,"PAGE ".$page." OF ".$totalpage);
    

           }
            
              }//end of additional pages
$pdf->Output("".$GRNnum."$GRNnum-MMS-GRN.pdf", "I");
?>
