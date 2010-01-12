<?php

extract($_REQUEST);
	 include('class/DBConn.php');
include("class/phpgraphlib.php"); 

 $POTotal['1']=0;    $POTotal['2'];    $POTotal['3']=0;    $POTotal['4']=0;
    $POTotal['5']=0;    $POTotal['6']=0;
    
    
    $sql ="SELECT doc_type, total, B.exchange FROM purchases A, currencies B
          where doc_tag='po'
          AND A.currency = B.id
          AND YEAR( A.po_date ) = $year
          AND MONTH( A.po_date ) = $month
          ORDER BY doc_type";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		
        //doc_type, total, currency, exchange
    		while ( $row = mysql_fetch_array($result))
    					{
    					$doc = $row['doc_type'];
    					
              $inRinggit = $row['total'] * $row['exchange'];
              $POTotal[$doc] = $POTotal[$doc] + $inRinggit;
    				}
    				
//header('Content-Type: image/png');
$graph=new PHPGraphLib(400,300);
$data=array("PO1"=>$POTotal['1'], "PO2"=>$POTotal['2'], "PO3"=>$POTotal['3'], 
              "PO4"=>$POTotal['4'], "PO5"=>$POTotal['5'], "PO6"=>$POTotal['6']);
$graph->addData($data);
$graph->setTitle("Purchase Order Report ");
$graph->setGradient("red", "maroon");
$graph->createGraph();
?>