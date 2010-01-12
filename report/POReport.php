<?php
session_start();
	 include('class/DBConn.php');
	 

extract($_REQUEST);
extract($_GET);
//error_reporting(E_ALL);

$header=" ";
$today = date("dMy");

       $timestamp = mktime(0, 0, 0, $month, 1, 2010);
    
    $mthName = date("F", $timestamp);

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
 

  if ( $op == 'view')
{

//echo $img;

?>

<img src="image.php?month=<?=$month?>&year=<?=$year?>" />
<br/><p/><a href=POReport.php?op=excel&month=<?=$month?>&year=<?=$year?>> Download as Excel file </a><p/>
<?php

}
else if ($op == 'excel')
{

/** PHPExcel */
include('class/PHPExcel.php');
/** PHPExcel_IOFactory */
include('class/PHPExcel/IOFactory.php');

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=PO-Report-$mthName.xls");
header('Cache-Control: max-age=0');



// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("UMW UPSB System MMS ")
							 ->setLastModifiedBy("PocketZila UPSB MMS 2.0")
							 ->setTitle("UPSB System MMS Purchase Order Report")
							 ->setSubject("UPSB System MMS Purchase Order Report")
							 ->setDescription("UPSB System MMS Purchase Order Report.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("UPSB System MMS Purchase Order Report");

//doc_type, total, currency, exchange
/*if ( $op == 'month')
{
$getTable = exportMTFTrack($month,$year);
$table = "  <td>#</td>
            <td bgcolor=blue>MTF Number</td>
            <td bgcolor=blue>Date</td>
            <td bgcolor=blue>Requester</td>
            <td bgcolor=blue>Approver</td>
            <td bgcolor=blue>Branch From</td>
            <td bgcolor=blue>Branch To</td>
            <td bgcolor=blue>Status</td>";
}else if ( $op == 'PI')
{
}
*/

 
  
			
              //$objPHPExcel->getActiveSheet()->mergeCells('C9:I9'); //merge
              
      $objDrawing = new PHPExcel_Worksheet_Drawing();
      
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    
    $objDrawing->setName('Paid');
    $objDrawing->setDescription('Paid');
    $objDrawing->setPath('umw.jpg');
    $objDrawing->setCoordinates('B2');
    $objDrawing->setOffsetX(110);
    $objDrawing->setRotation(25);
    $objDrawing->getShadow()->setVisible(true);
    $objDrawing->getShadow()->setDirection(45);

      
    	$objPHPExcel->setActiveSheetIndex(0);        
    $objPHPExcel->getActiveSheet()->setCellValue('B13', 'PO Category');
    $objPHPExcel->getActiveSheet()->setCellValue('C13', "$mthName");


          
           for ($i = 1; $i < 6; $i++) 
            {
            //echo "$i = ". $POTotal[$i];
            $place = $i +14;
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $place, "PO $i");
            
            $objPHPExcel->getActiveSheet()->getStyle('C' . $place)->getNumberFormat()
->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $objPHPExcel->getActiveSheet()->setCellValue('C' . $place, "$POTotal[$i]");
            //echo "<br/>";	
            }		
  
  //draw border
 $objPHPExcel->getActiveSheet()->getStyle('B22:J26')->applyFromArray(
	array(
		  'borders' => array(	
								'top'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )
	);
  
   $objPHPExcel->getActiveSheet()->getStyle('B29:C31')->applyFromArray(
	array(
		  'borders' => array(	
								'top'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )
	);
  
   $objPHPExcel->getActiveSheet()->getStyle('E29:F31')->applyFromArray(
	array(
		  'borders' => array(	
								'top'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )
	);
  
   $objPHPExcel->getActiveSheet()->getStyle('H29:I31')->applyFromArray(
	array(
		  'borders' => array(	
								'top'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							)
		 )
	);				


  $objPHPExcel->getActiveSheet()->setCellValue('B22', "Note :");
  $objPHPExcel->getActiveSheet()->setCellValue('B28', "Prepared By :");
  $objPHPExcel->getActiveSheet()->setCellValue('E28', "Check By :");
  $objPHPExcel->getActiveSheet()->setCellValue('H28', "Verified by :");
  



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');  

}
?>
