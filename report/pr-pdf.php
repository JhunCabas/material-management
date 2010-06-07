<?php
extract($_GET);
require('fpdf.php');
include_once('class/DBConn.php');

$doc_num = $PRnum;

////////////
$perpage = 9; 
$page = 1;
$limit = $perpage; 

$count ="SELECT DISTINCT COUNT(B.`doc_number`) AS count
            FROM purchases A, purchase_details B, branches C
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number
            AND A.branch_id = C.id";
       connectToDB();
                     $getCount = mysql_query($count) or die ("Execution SQL; count DISTINCT");
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
         
        $this->Image('UMW.jpeg',10,3,180,23); 
   //draw line
        $this->Line(10,60,10,180); //outer
        $this->Line(10,60,205,60);
        $this->Line(10,180,205,180);
        $this->Line(205,60,205,180);
        
        $this->Line(20,60,20,170); //1
        $this->Line(40,60,40,170); //2
        $this->Line(136,60,136,170); //3-qtty
        $this->Line(150,60,150,170); //4-uom
        $this->Line(165,60,165,170); //5
        $this->Line(185,60,185,170); //6
        
        $this->Line(10,73,205,73);
        $this->Line(10,170,205,170);
          
           
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



$starting = 82;
////////////

            $count = '0';
            //SQL  //gives the following  
            //   doc_number, quantity, unit_price, extended_price, item_id, doc_number, branch_id, currency, doc_date, branchLocation, branchNo, branchName, description, unit_of_measure, supplier_1, supplierContact, supplierNum, supplierName, supplierAddress
               
            $sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,B.`description`,
            A.`doc_number`, A.`branch_id`, A.`currency`, date_format(A.doc_date, '%D %M, %Y') as doc_date, A.payment, A.delivery, A.discount, A.total, A.special_instruction, A.mof_number,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            F.name as requester,
            G.country AS currency,
            A.supplier_1, A.supplier_2, A.supplier_3, E.contact_person as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, currencies G, purchase_details B, branches C, inv_items D, suppliers E, users F
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number
            AND F.username = A.requester
            AND A.branch_id = C.id
            AND A.supplier_1 = E.id
            AND B.item_id = D.id
            AND A.currency = G.id
            LIMIT 0,$limit";
                 
             connectToDB();
                     $getData = mysql_query($sql) or die ("Execution SQL; first round");
            while ( $row = mysql_fetch_array($getData))
            					{
            			 $count++;
            			 
            			$item_id = $row['item_id'];
            			$description = $row['description'];
                  $description = html_entity_decode($description, ENT_QUOTES);
            			$quantity = $row['quantity'];
            			$unitmeasure = $row['unit_of_measure'];
            			$unitprice = $row['unit_price'];
            		  $extendedprice = $row['extended_price'];
            		  
            		  $currency = $row['currency'];
            			$total  =$row['total'];
            			$discount = $row['discount'];
            			
                  //for MOF
                  $mof_number = $row['mof_number'];
            		  
            			$req_date = $row['requester_date'];
            			
                 // $unitprice = sprintf("%.2f",$unitprice);
                 $unitprice = number_format($unitprice, 2, '.', ','); 
                //  $total = sprintf("%.2f",$total);
                 $total = number_format($total, 2, '.', ','); 
                 // $extendedprice = sprintf("%.2f",$extendedprice);
                 $extendedprice = number_format($extendedprice, 2, '.', ','); 
                //  $discount = sprintf("%.2f",$discount);
                 $discount = number_format($discount, 2, '.', ','); 
            			
    
      
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
     
     
      $desc = str_split($description, 50);
     
     
      $pdf->Text(12,$starting,$count);
      $pdf->Text(22,$starting,$item_id);
      $pdf->Text(42,$starting,$desc[0]);
      $pdf->Text(140,$starting,$quantity);
      $pdf->Text(152,$starting,$unitmeasure);
      
       if (strlen($description) > 50 )
      {
       $starting = $starting + 5;
      $pdf->Text(42,$starting,$desc[1]);
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
                  
                  
            		  $supp_add1 = $row['add1'];
            		  $supp_add2 = $row['add2'];
            		  $supp_add3 = $row['add3'];
                  
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
  
    

$date = $doc_date;


            $pdf->Text(12,42,"Supplier 1 : ".$supplierName);
            
            
           $pdf->Text(12,46,"Supplier 2 : ".$sup2name);
           $pdf->Text(12,50,"Supplier 3 : ".$sup3name);
           
//$pdf->Text(22,24,$branchName);
$pdf->Text(12,32,"Ship To: ".$branchName);


$pdf->SetFont('Arial','B',11);
$pdf->Text(130,32,"PURCHASE REQUEST");
$pdf->SetFont('Arial','',10);

$pdf->Text(130,40,"Date: ".$date);
$pdf->Text(130,36,"P.R Number: ".$PRnum);

//for MOF
$pdf->Text(130,45,"MOF Number: ".$mof_number);

$pdf->Text(167,71,"( ".$currency." )");
$pdf->Text(187,71,"( ".$currency." )");

$pdf->Text(12,190,"SPECIAL INSTRUCTIONS AND TERMS");
$pdf->Text(12,194,$special_instruction);
$pdf->Text(170,275,"PAGE ".$page." OF ".$totalpage);
$pdf->Text(12,208,"Delivery Terms:");
$pdf->Text(12,210,"-----------------------");
$pdf->Text(12,214,$delivery);
$pdf->Text(130,208,"Payment Terms:");
$pdf->Text(130,210,"-----------------------");
$pdf->Text(130,214,$payment);
$pdf->Text(12,235,"Prepared By : ".$requester);
$pdf->Text(12,247,"Endorsed By : Karthigeyan Nallasamy /");
$pdf->Text(40,252," Chew Kwong Chee ");
$pdf->Text(12,263,"Approved By : Pendin Saragih");

$pdf->Text(130,235,"Date : ".$req_date);
$pdf->Text(130,247,"Date : ");
$pdf->Text(130,263,"Date : ");





////////////                                          ////////////
///do this if more than 10 items.///do this if more than 10 items.
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
///do this if more than 13 items.///do this if more than 13 items.
////////////                                          ////////////

    
if ($items > 1){


        for ($i = 1; $i < $items; $i++) 
            {
            
      $start=$start+$perpage;  //in case the limit we tuka, new page start item from previouss
                
      $page += 1;
    //set start ,limit
    //$start=$start+$perpage; //this created the wrong sql - 5th Jan 2010
    
            
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
    
    
    
    /////////SQL
    ////// data comes here
              
            $starting = 82;
            unset($sql);
            unset($row);
            unset($getData);
            
            unset($desc);
          $sql ="SELECT B.`doc_number`, B.`quantity`, B.`unit_price`, B.`extended_price`,B.`item_id`,B.`description`,
            A.`doc_number`, A.`branch_id`, A.`currency`, date_format(A.doc_date, '%D %M, %Y') as doc_date, A.payment, A.delivery, A.discount, A.total, A.special_instruction, A.mof_number,
            C.`location`as branchLocation, C.`phone_no` as branchNo, C.`name` as branchName,
            D.`unit_of_measure`,
            F.name as requester,
            G.country AS currency,
            A.supplier_1, A.supplier_2, A.supplier_3, E.contact_person as supplierContact, E.contact as supplierNum, E.name as supplierName, E.line_1 as add1, E.line_2 as add2, E.line_3 as add3, E.fax_no
            FROM purchases A, currencies G, purchase_details B, branches C, inv_items D, suppliers E, users F
            WHERE A.doc_number='$doc_num'
            AND B.doc_number = A.doc_number
            AND F.username = A.requester
            AND A.branch_id = C.id
            AND A.supplier_1 = E.id
            AND B.item_id = D.id
            AND A.currency = G.id
            LIMIT $start,$limit";
                 
             connectToDB();
                     $getData = mysql_query($sql) or die ("Execution SQL; round $i");
            while ( $row = mysql_fetch_array($getData))
            					{
            			 $count++;
            			 
            			$item_id = $row['item_id'];
            			$description = $row['description'];
                  $description = html_entity_decode($description);
            			$quantity = $row['quantity'];
            			$unitmeasure = $row['unit_of_measure'];
            			$unitprice = $row['unit_price'];
            		  $extendedprice = $row['extended_price'];
            		  
            		  $currency = $row['currency'];
            			$total = $row['total'];
            			$discount = $row['discount'];
            			
            			$req_date = $row['requester_date'];
            			
                  //for MOF
                  
            		  $mof_number = $row['mof_number'];
            		  
                 // $unitprice = sprintf("%.2f",$unitprice);
                 $unitprice = number_format($unitprice, 2, '.', ','); 
                //  $total = sprintf("%.2f",$total);
                 $total = number_format($total, 2, '.', ','); 
                 // $extendedprice = sprintf("%.2f",$extendedprice);
                 $extendedprice = number_format($extendedprice, 2, '.', ','); 
                //  $discount = sprintf("%.2f",$discount);
                 $discount = number_format($discount, 2, '.', ','); 
            			
    
      
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
                 
                 
                  $desc = str_split($description, 52);
                 
                 
                  $pdf->Text(12,$starting,$count);
                  $pdf->Text(22,$starting,$item_id);
                  $pdf->Text(42,$starting,$desc[0]);
                  $pdf->Text(140,$starting,$quantity);
                  $pdf->Text(152,$starting,$unitmeasure);
                  
                   if (strlen($description) > 52 )
                  {
                   $starting = $starting + 5;
                  $pdf->Text(42,$starting,$desc[1]);
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
                              
                              
                        		  $supp_add1 = $row['add1'];
                        		  $supp_add2 = $row['add2'];
                        		  $supp_add3 = $row['add3'];
                              
                              $payment = $row['payment'];
                              $delivery = $row['delivery'];
                              $special_instruction = $row['special_instruction'];
                              
                              			}
            
            ////////////
            
            
             
                
            
            $date = $doc_date;
            
            
                        $pdf->Text(12,42,"Supplier 1 : ".$supplierName);
                        
                        
                       $pdf->Text(12,46,"Supplier 2 : ".$sup2name);
                       $pdf->Text(12,50,"Supplier 3 : ".$sup3name);
                       
            //$pdf->Text(22,24,$branchName);
            $pdf->Text(12,32,"Ship To: ".$branchName);
            
            
            $pdf->SetFont('Arial','B',11);
            $pdf->Text(130,32,"PURCHASE REQUEST");
            $pdf->SetFont('Arial','',10);
            
            $pdf->Text(130,40,"Date: ".$date);
            $pdf->Text(130,36,"P.R Number: ".$PRnum);
            
//for MOF
$pdf->Text(130,45,"MOF Number: ".$mof_number);
            
            $pdf->Text(167,71,"( ".$currency." )");
            $pdf->Text(187,71,"( ".$currency." )");
            
            $pdf->Text(12,190,"SPECIAL INSTRUCTIONS AND TERMS");
            $pdf->Text(12,194,$special_instruction);
            $pdf->Text(170,275,"PAGE ".$page." OF ".$totalpage);
            $pdf->Text(12,208,"Delivery Terms:");
            $pdf->Text(12,210,"-----------------------");
            $pdf->Text(12,214,$delivery);
            $pdf->Text(130,208,"Payment Terms:");
            $pdf->Text(130,210,"-----------------------");
            $pdf->Text(130,214,$payment);
            $pdf->Text(12,235,"Prepared By : ".$requester);
            $pdf->Text(12,247,"Endorsed By : Karthigeyan Nallasamy /");
            $pdf->Text(40,252," Chew Kwong Chee ");
            $pdf->Text(12,263,"Approved By : Pendin Saragih");
            
            $pdf->Text(130,235,"Date : ".$req_date);
            $pdf->Text(130,247,"Date : ");
            $pdf->Text(130,263,"Date : ");

  
    
           }
            
              }//end of additional pages








$pdf->Text(167,175,'TOTAL');
$pdf->Text(100,168,'DISCOUNT');


$pdf->Text(187,175,$total);
$pdf->Text(187,165,"($discount)");
$pdf->Output("".$PRnum.".pdf", "I");
?>
