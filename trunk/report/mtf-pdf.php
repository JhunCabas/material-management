<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$doc_num = $MTFnum;

////////////
$perpage = 16; 
$limit = $perpage;
$page = 1;

$count ="SELECT DISTINCT COUNT(B.`doc_number`) AS count
            FROM material_transfers A, material_transfer_details B
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number ;";
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
            // doc_number, doc_date, doc_type, branch_id, branch_from, branch_to, approver, approver_date,
            // requester, requester_date, status, 
            // -- id, doc_number, item_id, quantity, remark, from_branch, status, 
            // -- id, name, location, phone_no, 
            // -- description, unit_of_measure, doc_date  
            $sql ="select A.*,date_format(A.requester_date, '%D %M, %Y') AS requester_date, date_format(A.approver_date, '%D %M, %Y') AS approver_date, B.*,C.*, D.description, D.unit_of_measure, date_format(A.doc_date, '%D %M, %Y') as doc_date
FROM material_transfers A, material_transfer_details B,branches C, inv_items D
WHERE A.doc_number='$doc_num'
AND A.doc_number = B.doc_number
AND A.branch_from = C.id
AND B.item_id= D.id
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
            		  
            			
            			$approver = $row['approver'];
            			$approver_date = $row['approver_date'];
            		$requester = $row['requester'];
            		$requester_date = $row['requester_date'];
            			
            		$doc_date = $row['doc_date'];
            			$branch_from = $row['name'];
            			$branch_to = $row['branch_to'];
            			
            			
            			$branch_from_loc = $row['location'];
            		//$branch_to = $row['branch_to'];
                 
                  $remark = $row['remark'];
            			
     $remarke = str_split($remark, 21);
     
       $unitStart =  0;
       
                 
      $desc = str_split($description, 52);
     
     
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(46,$starting,$desc[0]);
      $pdf->Text(138,$starting,$quantity);
      $pdf->Text(153,$starting,$unitmeasure);
      $pdf->Text(166,$starting,$remarke[0]);
      
       if (strlen($remark) > 21 )
      {
       $unitStart = $starting + 5;
      $pdf->Text(166,$unitStart,$remarke[1]);
       $unitStart =  5;
      
       }
       
       if (strlen($remark) > 42 )
      {
       $unitStart = $starting + 10;
      $pdf->Text(166,$unitStart,$remarke[2]);
       $unitStart =  10;
       }
      
       if (strlen($description) > 52 )
      {
       $unitStart = $starting + 5;
      $pdf->Text(46,$unitStart,$desc[1]);
       $unitStart =  5;
       }
      
    $starting = $starting + $unitStart + 5;
            			
            			
               
                  
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
$pdf->Text(130,32,"MATERIAL TRANSFER FORM");


$pdf->SetFont('Arial','',10);
     $desc = str_split($description, 52);
      
$pbranchAdd= split(",",$branch_from_loc); 


$pdf->Text(10,32,"From Branch : ".$branch_from.", ".$pbranchAdd[0]); 

$pdf->Text(10,36,"".$pbranchAdd[1].", ".$pbranchAdd[2].", ".$pbranchAdd[3]);
//$pdf->Text(10,39,"".$pbranchAdd[4]);     
      
$pdf->Text(10,42,"To Branch : ".$branch_to);


$pdf->Text(130,37,"MTF No: ".$MTFnum);
$pdf->Text(130,42,"Doc Date: ".$date);



$pdf->SetFont('Arial','B',9);
$pdf->Text(12,235,"Issued By : ");
$pdf->Text(130,235,"Received  By : ");

$pdf->SetFont('Arial','',10);
$pdf->Text(12,240,"Name : ".$requester);
$pdf->Text(12,244,"Date : ".$requester_date);
$pdf->Text(12,253,"Signature ");


$pdf->Text(130,240,"Name : ".$approver);
$pdf->Text(130,244,"Date : ".$approver_date);
$pdf->Text(130,253,"Signature ");


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

           
            //SQL  //gives the following  
            // doc_number, doc_date, doc_type, branch_id, branch_from, branch_to, approver, approver_date,
            // requester, requester_date, status, 
            // -- id, doc_number, item_id, quantity, remark, from_branch, status, 
            // -- id, name, location, phone_no, 
            // -- description, unit_of_measure, doc_date  
            $sql ="select A.*,date_format(A.requester_date, '%D %M, %Y') AS requester_date, date_format(A.approver_date, '%D %M, %Y') AS approver_date, B.*,C.*, D.description, D.unit_of_measure, date_format(A.doc_date, '%D %M, %Y') as doc_date
FROM material_transfers A, material_transfer_details B,branches C, inv_items D
WHERE A.doc_number='$doc_num'
AND A.doc_number = B.doc_number
AND A.branch_from = C.id
AND B.item_id= D.id
            LIMIT $start,$perpage";
                 
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
            		  
            			
            			$approver = $row['approver'];
            			$approver_date = $row['approver_date'];
            		$requester = $row['requester'];
            		$requester_date = $row['requester_date'];
            			
            		$doc_date = $row['doc_date'];
            			$branch_from = $row['name'];
            			$branch_to = $row['branch_to'];
            			
            			
            			$branch_from_loc = $row['location'];
            		//$branch_to = $row['branch_to'];
                 
                  $remark = $row['remark'];
            			
       $remarke = str_split($remark, 21);
     
       $unitStart =  0;
       
                 
      $desc = str_split($description, 52);
     
     
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(46,$starting,$desc[0]);
      $pdf->Text(138,$starting,$quantity);
      $pdf->Text(153,$starting,$unitmeasure);
      $pdf->Text(166,$starting,$remarke[0]);
      
       if (strlen($remark) > 21 )
      {
       $unitStart = $starting + 5;
      $pdf->Text(166,$unitStart,$remarke[1]);
       $unitStart =  5;
      
       }
       
       if (strlen($remark) > 42 )
      {
       $unitStart = $starting + 10;
      $pdf->Text(166,$unitStart,$remarke[2]);
       $unitStart =  10;
       }
      
       if (strlen($description) > 52 )
      {
       $unitStart = $starting + 5;
      $pdf->Text(46,$unitStart,$desc[1]);
       $unitStart =  5;
       }
      
    $starting = $starting + $unitStart + 5;
            			
            			
            			
               
                  
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
$pdf->Text(130,32,"MATERIAL TRANSFER FORM");


$pdf->SetFont('Arial','',10);
     $desc = str_split($description, 52);
      
$pbranchAdd= split(",",$branch_from_loc); 


$pdf->Text(10,32,"From Branch : ".$branch_from.", ".$pbranchAdd[0]); 

$pdf->Text(10,36,"".$pbranchAdd[1].", ".$pbranchAdd[2].", ".$pbranchAdd[3]);
//$pdf->Text(10,39,"".$pbranchAdd[4]);     
      
$pdf->Text(10,42,"To Branch : ".$branch_to);


$pdf->Text(130,37,"MTF No: ".$MTFnum);
$pdf->Text(130,42,"Doc Date: ".$date);



$pdf->SetFont('Arial','B',9);
$pdf->Text(12,235,"Issued By : ");
$pdf->Text(130,235,"Received  By : ");

$pdf->SetFont('Arial','',10);
$pdf->Text(12,240,"Name : ".$requester);
$pdf->Text(12,244,"Date : ".$requester_date);
$pdf->Text(12,253,"Signature ");


$pdf->Text(130,240,"Name : ".$approver);
$pdf->Text(130,244,"Date : ".$approver_date);
$pdf->Text(130,253,"Signature ");


$pdf->Line(10,265,205,265);

$pdf->Text(15,270,$date);
$pdf->Text(170,270,"PAGE ".$page." OF ".$totalpage);


            

           }
            
              }//end of additional pages
              
$pdf->Output("".$MTFnum.".pdf", "I");
?>
