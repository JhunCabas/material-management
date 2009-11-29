<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$doc_num = $POnum;

////////////
$perpage = 13; 
$page = 1;

$count ="SELECT DISTINCT COUNT(B.`doc_number`) AS count
            FROM purchases A, purchase_details B, branches C
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number
            AND A.branch_id = C.id";
       connectToDB();
                     $getCount = mysql_query($count) or die ("Execution SQL; error");
             $rowCount = mysql_fetch_array($getCount);
            				
            	$items = $rowCount['count'];
               $items = $items/$perpage; 
               $totalpage = ceil($items);

//////////////////////
/////////////////////

class PDF extends FPDF
{
//Page header
function Header()
{
            //draw line
        $this->Line(10,60,10,195); //outer
        $this->Line(10,60,205,60);
        $this->Line(10,195,205,195);
        $this->Line(205,60,205,195);
        
        $this->Line(20,60,20,183); //1
        $this->Line(40,60,40,183); //2
        $this->Line(136,60,136,183); //3-qtty
        $this->Line(150,60,150,183); //4-uom
        $this->Line(165,60,165,183); //5
        $this->Line(185,60,185,183); //6
        
        $this->Line(10,70,205,70);
        $this->Line(10,183,205,183);
          
           
      }

//Page footer
function Footer()
    {      
    }
}

//Instanciation of inherited class
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('P','Letter');
      $pdf->SetFont('Arial','',10);



//table title
$pdf->Text(12,64,'NO');
$pdf->Text(22,64,'ITEM');
$pdf->Text(22,67,'CODE');
$pdf->Text(42,64,'DESCRIPTION');
$pdf->Text(137,64,'QTY');
$pdf->Text(152,64,'UOM');
$pdf->Text(167,64,'UNIT');
$pdf->Text(167,67,'PRICE');
$pdf->Text(187,64,'EXT.');
$pdf->Text(187,67,'PRICE');



////////////


$starting = 74;

            $count = '0';
            //SQL  //gives the following  
            //   doc_number, quantity, unit_price, extended_price, item_id, doc_number, branch_id, currency, doc_date, branchLocation, branchNo, branchName, description, unit_of_measure, supplier_1, supplierContact, supplierNum, supplierName, supplierAddress
               
            $sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,B.`description`,
            A.`po_number`, A.`branch_id`, A.`currency`, date_format(A.po_date, '%D %M, %Y') as doc_date, A.payment, A.delivery, A.discount, A.total, A.special_instruction,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            A.supplier_1, E.contact_person as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, purchase_details B, branches C, inv_items D, suppliers E
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number
            AND A.branch_id = C.id
            AND A.supplier_1 = E.id
            AND B.item_id = D.id
            LIMIT 0,$perpage";
                 
             connectToDB();
                     $getData = mysql_query($sql) or die ("Execution SQL; error");
            while ( $row = mysql_fetch_array($getData))
            					{
            			 $count++;
            			 
            			$item_id = $row['item_id'];
            			$description = addslashes($row['description']);
                  $description = html_entity_decode($description);
            			$quantity = $row['quantity'];
            			$unitmeasure = $row['unit_of_measure'];
            			$unitprice = $row['unit_price'];
            		  $extendedprice = $row['extended_price'];
            		  
            			$total = $row['total'];
            			$discount = $row['discount'];
            			$POnum = $row['po_number'];
            			
                 $unitprice = number_format($unitprice, 2, '.', ','); 
                 $total = number_format($total, 2, '.', ','); 
                 $extendedprice = number_format($extendedprice, 2, '.', ','); 
                 $discount = number_format($discount, 2, '.', ','); 
      
      
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
                
                  
            		  $supp_add1 = $row['add1'];
            		  $supp_add2 = $row['add2'];
            		  $supp_add3 = $row['add3'];
                  
                  $payment = $row['payment'];
                  $delivery = $row['delivery'];
                  $special_instruction = $row['special_instruction'];
                  
                  		
             if (strlen($unitprice) > 7 )
              {     
            $pdf->Text(167,$starting,$unitprice);
              }else if (strlen($unitprice) > 6 )
              {  
            $pdf->Text(168.5,$starting,$unitprice);
              }else if (strlen($unitprice) > 5 )
              {    
            $pdf->Text(170,$starting,$unitprice);
              }else 
              {     
            $pdf->Text(172,$starting,$unitprice);
              }
              //////////////////////////////
              //////////////////////////////
              if (strlen($extendedprice) > 7 )
              {     
              $pdf->Text(187,$starting,$extendedprice);
              }else if (strlen($extendedprice) > 6 )
              {  
              $pdf->Text(198.5,$starting,$extendedprice);
              }else if (strlen($extendedprice) > 5 )
              {    
              $pdf->Text(190,$starting,$extendedprice); 
              }else 
              {     
              $pdf->Text(192,$starting,$extendedprice);
              }      			
             
              $desc = str_split($description, 54);
             
              $pdf->Text(12,$starting,$count);
              $pdf->Text(22,$starting,$item_id);
              $pdf->Text(42,$starting,$desc[0]);
              $pdf->Text(139,$starting,$quantity);
              $pdf->Text(154,$starting,$unitmeasure);
              
               if (strlen($description) > 54 )
              {
               $starting = $starting + 5;
              $pdf->Text(42,$starting,$desc[1]);
               }
               
            $starting = $starting + 5;
                      
                  	}

 
//this is sample data, change this code and replace the data from mysql

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
$pdf->Text(130,24,"PO No: ".$POnum);

$pdf->Text(12,203,"SPECIAL INSTRUCTIONS AND TERMS");
$pdf->Text(12,207,$special_instruction);
$pdf->Text(160,203,"PAGE ".$page." OF ".$totalpage);
$pdf->Text(12,219,"Delivery Terms:");
$pdf->Text(12,221,"-----------------------");
$pdf->Text(12,225,$delivery);
$pdf->Text(130,219,"Payment Terms:");
$pdf->Text(130,221,"-----------------------");
$pdf->Text(130,225,$payment);




////////////                                          ////////////
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
////////////                                          ////////////

    $start=$limit;  //in case the limit we tuka, new page start item from previouss
    
if ($items > 1){


        for ($i = 1; $i < $items; $i++) 
            {
            
       $start=$limit;  //in case the limit we tuka, new page start item from previouss
                
      $page += 1;
    //set start ,limit
    $limit=$start+$perpage;
    
            
      $pdf->AddPage('P','Letter');
      $pdf->SetFont('Arial','',10);
    
          
      //table title
      $pdf->Text(12,64,'NO');
      $pdf->Text(22,64,'ITEM');
      $pdf->Text(22,67,'CODE');
      $pdf->Text(42,64,'DESCRIPTION');
      $pdf->Text(137,64,'QTY');
      $pdf->Text(152,64,'UOM');
      $pdf->Text(167,64,'UNIT');
      $pdf->Text(167,67,'PRICE');
      $pdf->Text(187,64,'EXT.');
      $pdf->Text(187,67,'PRICE');
      $pdf->Text(167,190,'TOTAL');
      $pdf->Text(100,180,'DISCOUNT');
    
    
    
    /////////SQL
    ////// data comes here
              
            $starting = 74;
            unset($sql);
            unset($row);
            unset($getData);
            
            unset($desc);
            
            //SQL  //gives the following  
            //   doc_number, quantity, unit_price, extended_price, item_id, doc_number, branch_id, currency, doc_date, branchLocation, branchNo, branchName, description, unit_of_measure, supplier_1, supplierContact, supplierNum, supplierName, supplierAddress
            $sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,B.`description`,
            A.`po_number`, A.`branch_id`, A.`currency`, date_format(A.doc_date, '%D %M, %Y') as doc_date, A.payment, A.delivery, A.discount, A.total, A.special_instruction,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            A.supplier_1, E.contact_person as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, purchase_details B, branches C, inv_items D, suppliers E
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number
            AND A.branch_id = C.id
            AND A.supplier_1 = E.id
            AND B.item_id = D.id
            LIMIT $start,$limit";
                 
             connectToDB();
                     $getData = mysql_query($sql) or die ("Execution SQL; error");
            while ( $row = mysql_fetch_array($getData))
            					{
            			 $count++;
            			 
            			$item_id = $row['item_id'];
            			$description = addslashes($row['description']);
                  $description = html_entity_decode($description);
            			$quantity = $row['quantity'];
            			$unitmeasure = $row['unit_of_measure'];
            			$unitprice = $row['unit_price'];
            		  $extendedprice = $row['extended_price'];
            		  
            			$total = $row['total'];
            			$discount = $row['discount'];
            			$POnum = $row['po_number'];
            			
                 $unitprice = number_format($unitprice, 2, '.', ','); 
                 $total = number_format($total, 2, '.', ','); 
                 $extendedprice = number_format($extendedprice, 2, '.', ','); 
                 $discount = number_format($discount, 2, '.', ','); 
      
      
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
                
                  
            		  $supp_add1 = $row['add1'];
            		  $supp_add2 = $row['add2'];
            		  $supp_add3 = $row['add3'];
                  
                  $payment = $row['payment'];
                  $delivery = $row['delivery'];
                  $special_instruction = $row['special_instruction'];
                  
                  		
             if (strlen($unitprice) > 7 )
              {     
            $pdf->Text(167,$starting,$unitprice);
              }else if (strlen($unitprice) > 6 )
              {  
            $pdf->Text(168.5,$starting,$unitprice);
              }else if (strlen($unitprice) > 5 )
              {    
            $pdf->Text(170,$starting,$unitprice);
              }else 
              {     
            $pdf->Text(172,$starting,$unitprice);
              }
              //////////////////////////////
              //////////////////////////////
              if (strlen($extendedprice) > 7 )
              {     
              $pdf->Text(187,$starting,$extendedprice);
              }else if (strlen($extendedprice) > 6 )
              {  
              $pdf->Text(198.5,$starting,$extendedprice);
              }else if (strlen($extendedprice) > 5 )
              {    
              $pdf->Text(190,$starting,$extendedprice); 
              }else 
              {     
              $pdf->Text(192,$starting,$extendedprice);
              }      			
             
              $desc = str_split($description, 54);
             
              $pdf->Text(12,$starting,$count);
              $pdf->Text(22,$starting,$item_id);
              $pdf->Text(42,$starting,$desc[0]);
              $pdf->Text(139,$starting,$quantity);
              $pdf->Text(154,$starting,$unitmeasure);
              
               if (strlen($description) > 54 )
              {
               $starting = $starting + 5;
              $pdf->Text(42,$starting,$desc[1]);
               }
               
            $starting = $starting + 5;
                      
                  	}

  
    
    //my details + footer\
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
            $pdf->Text(130,24,"PO No: ".$POnum);
            
            $pdf->Text(12,203,"SPECIAL INSTRUCTIONS AND TERMS");
            $pdf->Text(12,207,$special_instruction);
            $pdf->Text(160,203,"PAGE ".$page." OF ".$totalpage);
            $pdf->Text(12,219,"Delivery Terms:");
            $pdf->Text(12,221,"-----------------------");
            $pdf->Text(12,225,$delivery);
            $pdf->Text(130,219,"Payment Terms:");
            $pdf->Text(130,221,"-----------------------");
            $pdf->Text(130,225,$payment);
            
            

           }
            
              }//end of additional pages



$pdf->Text(167,190,'TOTAL');
$pdf->Text(100,180,'DISCOUNT');

$pdf->Text(187,190,$total);
$pdf->Text(187,180,"($discount)");





$pdf->Output("".$POnum.".pdf", "I");
?>