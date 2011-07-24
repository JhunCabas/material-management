<?php
	 include('class/DBConn.php');
	 //require ('vars.php');
session_start();

###################################
##### inv items Vs Purchase dates #####
###################################
    	function itemsPurchaseDate()
    		{
    $sql ="SELECT COUNT(item_code) as item,purchase_year FROM umw_mms.inv_items i GROUP BY purchase_year";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    			$name .= "<set name='".$row['purchase_year']."' value='".$row['item']."'color='" .getFCColor() . "' />";
    						}
    			return $name;			
    			}
			
###################################			
##### Items by Active,Inactive etc #####
###################################
    	function itemActiveInactive()
    		{
    $sql ="	SELECT DISTINCT COUNT(id) as total, status
          FROM umw_mms.inv_items A
          GROUP BY A.status";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    					if ($row['status'] == '1')
               $status = 'Active';
              else if ($row['status'] == '0')
               $status = 'Inactive';
    			$name .= "<set name='$status' value='".$row['total']."'color='" .getFCColor() . "' />";
    						}
    			return $name;			
    			}			

###################################
###### export Inventory Items to Excel######
###################################
    	function exportInv()
    		{
    		$count='1';
        $sql ="	SELECT * FROM umw_mms.inv_items A order by id";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['id']."</td>
              <td>".$row['description']."</td>
              <td>".$row['weight']."</td>
              <td>".$row['dimension']."</td>
              <td>".$row['part_number']."</td>
              <td>".$row['unit_of_measure']."</td>
              <td>".$row['rate']."</td>
              <td>".$row['currency']."</td>
              <td>".$row['purchase_year']."</td>
              <td>".$row['detailed_description']."</td>
              <td>".$row['status']."</td>";
          
    	   $count++;
         }
         return $table;
      }		
 

###################################
###### export Branch Stocks to Excel######
#### id, branch_id, item_id, quantity ####
###################################
    	function exportStock()
    		{
    		$count='1';
        $sql ="	SELECT * FROM umw_mms.inv_stocks A order by branch_id, item_id";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['branch_id']."</td>
              <td>".$row['item_id']."</td>
              <td>".$row['quantity']."</td>";
          
    	   $count++;
         }
         return $table;
      }		
 
  
###################################
##### Category Values #####
###################################
    	function CategoryValue()
    		{
    $sql ="select A.main_category_code, sum(A.rate) as total, B.description
          from umw_mms.inv_items A,umw_mms.inv_maincategories B
          WHERE A.main_category_code = B.category_code
          group by A.main_category_code";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    			$name .= "<set name='".$row['main_category_code']."-".$row['description']."' value='".$row['total']."'color='" .getFCColor() . "' />";
    						}
    			return $name;			
    			}

  
###################################
##### Branch Values #####
###################################
    	function BranchValue()
    		{
    $sql ="select branch_id, sum(rate) as total
            from umw_mms.inv_stocks a
            join umw_mms.inv_items b on item_id = b.id
            group by branch_id";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    			while ( $row = mysql_fetch_array($result))
    					{
    			$name .= "<set name='".$row['branch_id']."' value='".$row['total']."'color='" .getFCColor() . "' />";
    						}
    			return $name;				
    			}

			
 ////////////////////////////////////
 //////////TABLE GENERATION//////////
 //////////////////////////////////// 
     
###################################			
##### Table : ActiveInactive #####
###################################
    	function tableActiveInactive()
    		{
    $sql ="	SELECT DISTINCT COUNT(id) as total, status
          FROM umw_mms.inv_items A
          GROUP BY A.status";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    			if ($row['status'] == '1')
               $status = 'Active';
              else if ($row['status'] == '0')
               $status = 'Inactive';
               
        $table .= "
      	<tr bgcolor=\"#FFFFFF\" align=\"center\">
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$status." </font></td>
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['total']."</font></div></td>
                         
       </tr>
      					";
					
    						}
    			return $table;			
    			}		      
      
###################################			
##### Table : PuchaseDate #####
###################################
    	function tablePurchaseDate()
    		{
       $sql ="SELECT COUNT(item_code) as item,purchase_year FROM umw_mms.inv_items i GROUP BY purchase_year";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    	
        $table .= "
      	<tr bgcolor=\"#FFFFFF\" align=\"center\">
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['purchase_year']." </font></td>
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['item']."</font></div></td>
                         
       </tr>
      					";
					
    						}
    			return $table;			
    			}	
  
###################################
##### Category Values #####
###################################
    	function tableCategoryValue()
    		{
    $sql ="select A.main_category_code, sum(A.rate) as total, B.description
          from umw_mms.inv_items A,umw_mms.inv_maincategories B
          WHERE A.main_category_code = B.category_code
          group by A.main_category_code";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    			 $table .= "
      	<tr bgcolor=\"#FFFFFF\" align=\"center\">
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['main_category_code']."-".$row['description']." </font></td>
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['total']."</font></div></td>
                         
       </tr>		";
              
              	}
    			return $table;			
    			}

  
###################################
##### Branch Values #####
###################################
    	function tableBranchValue()
    		{
    $sql ="select branch_id, sum(rate) as total
            from umw_mms.inv_stocks a
            join umw_mms.inv_items b on item_id = b.id
            group by branch_id";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    					{
    			 $table .= "
      	<tr bgcolor=\"#FFFFFF\" align=\"center\">
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['branch_id']." </font></td>
                            
                            <td><font face=\"Verdana, Arial, Helvetica, sans-serif\"> ".$row['total']."</font></div></td>
                         
       </tr>		";
              
              	}
    			return $table;			
    			}


###################################
###### export Monthly Tracker to Excel######
#  doc_number, po_number, doc_date, po_date, grn_number,grn_date, status
###################################
    	function exportTracker($month,$year)
    		{
    		$count='1';
      $sql ="	select A.doc_number,A.po_number, A.doc_date, A.po_date, B.doc_number as grn_number,B.doc_date AS grn_date, A.status
                from purchases A left join good_receipt_notes B on A.po_number=B.po_no
                WHERE YEAR( A.doc_date ) = $year 
                AND MONTH( A.doc_date ) = $month 
                order by A.doc_date";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['doc_number']."</td>
              <td>".$row['doc_date']."</td>
              <td>".$row['po_number']."</td>
              <td>".$row['po_date']."</td>
              <td>".$row['grn_number']."</td>
              <td>".$row['grn_date']."</td>
              <td>".$row['status']."</td>";
          
    	   $count++;
         }
         return $table;
      }		
 


###################################
###### export Monthly Tracker to Excel######
#  doc_number, doc_date, doc_type, branch_id, branch_from, branch_to,
#  approver, approver_date, requester, requester_date, status, id, name, 
#  location, phone_no
###################################

    	function exportMTFTrack($month,$year)
    		{
    		$count='1';
      $sql ="	select A.*,C.*
              FROM material_transfers A,branches C
              WHERE YEAR( A.doc_date ) = $year 
                AND MONTH( A.doc_date ) = $month
              AND A.branch_from = C.id
              ORDER BY status";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['doc_number']."</td>
              <td>".$row['doc_date']."</td>
              <td>".$row['requester']."</td>
              <td>".$row['approver']."</td>
              <td>".$row['branch_from']."</td>
              <td>".$row['branch_to']."</td>
              <td>".$row['status']."</td>";
          
    	   $count++;
         }
         return $table;
      }		
      
      
###################################
###### export Monthly Tracker to Excel######
#  doc_number, doc_date, doc_type, branch_id, branch_from, branch_to,
#  approver, approver_date, requester, requester_date, status, id, name, 
#  location, phone_no
###################################

    	function exportPITrack($month,$year)
    		{
    		$count='1';
      $sql ="	select A.*,C.*
              FROM production_issues A,branches C
              WHERE YEAR( A.doc_date ) = $year
                AND MONTH( A.doc_date ) = $month
              AND A.branch_id = C.id
              ORDER BY status";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['doc_number']."</td>
              <td>".$row['doc_date']."</td>
              <td>".$row['issuer']."</td>
              <td>".$row['branch_id']."</td>
              <td>".$row['status']."</td>";
          
    	   $count++;
         }
         return $table;
      }		

//added for MOF 

###################################
###### export Monthly MOF Tracker to Excel######
#  mof_number, doc_number, po_number, doc_date, po_date, grn_number,grn_date, status
###################################
    	function exportMOFTracker($month,$year)
    		{
    		$count='1';
      $sql ="	select A.mof_number,A.doc_number,A.po_number, A.doc_date, A.po_date, B.doc_number as grn_number,B.doc_date AS grn_date, A.status
                from purchases A left join good_receipt_notes B on A.po_number=B.po_no
                WHERE YEAR( A.doc_date ) = $year 
                AND MONTH( A.doc_date ) = $month 
                order by A.doc_date";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['mof_number']."</td>
              <td>".$row['doc_number']."</td>
              <td>".$row['doc_date']."</td>
              <td>".$row['po_number']."</td>
              <td>".$row['po_date']."</td>
              <td>".$row['grn_number']."</td>
              <td>".$row['grn_date']."</td>
              <td>".$row['status']."</td>";
          
    	   $count++;
         }
         return $table;
      }		
 
//added for Supplier Exporting 

###################################
###### export Supplier Listing to Excel######
#  mof_number, doc_number, po_number, doc_date, po_date, grn_number,grn_date, status
###################################
    	function getSupplierList()
    		{
    		$count='1';
            $sql ="	SELECT * 
                    FROM  `suppliers`";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
                <td>$count</td>
              <td>".$row['name']."</td>
              <td>".$row['address']."</td>
              <td>".$row['line_1'].",".$row['line_2'].",".$row['line_3']."</td>
              <td>".$row['contact_person']."</td>
              <td>".$row['contact']."</td>
              <td>".$row['info']."</td>
              <td>".$row['fax_no']."</td>
              <td>".$row['status']."</td>";
     
    	   $count++;
         }
         return $table;
      }		
 
//added for Supplier Purchase Exporting

###################################
###### export Supplier Purchase Listing to Excel######
#  header (PO number), supplier, counter, total
###################################
    	function getSupplierPurchaseList($month,$year)
    		{
    		$count='1';
            $sql ="
            SELECT header, supplier, counter, total FROM (
            
            SELECT ' ' AS header, p.doc_type, s.name AS supplier, CAST(COUNT(p.po_number) AS CHAR) AS counter,
            CAST(ROUND(SUM(p.total * c.exchange), 2) AS DECIMAL(14,2)) AS total
            FROM purchases p, suppliers s, currencies c
            WHERE p.supplier_1 = s.id
            AND p.currency = c.id
            AND p.doc_tag = 'po'
            AND YEAR(p.po_date) = '$year'
            AND MONTH(p.po_date) = '$month'
            AND (p.status ='completed' OR p.status = 'approved')
            GROUP BY p.doc_type, p.supplier_1
            
            UNION
            
            SELECT CONCAT('PO', doc_type) AS header, doc_type, ' '  AS supplier, ' ' AS counter, ' ' AS total
            FROM purchases
            WHERE doc_tag = 'po'
            AND YEAR(po_date) = '$year'
            AND MONTH(po_date) = '$month'
            GROUP BY doc_type
            
            ) AS tmp
            ORDER BY doc_type ASC, supplier ASC, counter ASC, total ASC	
            
            ";
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
              <td>".$row['header']."</td>
              <td>".$row['supplier']."</td>
              <td>".$row['counter']."</td>
              <td>".$row['total']."</td>";
     
    	   $count++;
         }
         return $table;
      }		
 
 //added for Stock Tracker cron

###################################
###### export Stock Tracker cronjob to Excel######
#  header branch_id, item_id, quantity, transit
###################################
    	function getStockTracker()
    		{
    		$count='1';
            $sql ="
            SELECT * FROM inv_stocks i
            where quantity =0
            and transit =0
            order by branch_id asc";
            
    			 connectToDB();
            $result = mysql_query($sql) or die ("error");
    		while ( $row = mysql_fetch_array($result))
    			{
    	
      		$table .= "
            <tr bgcolor=\"#FFFFFF\"> 
              <td>".$row['branch_id']."</td>
              <td>".$row['item_id']."</td>
              <td>".$row['quantity']."</td>";
     
    	   $count++;
         }
         return $table;
      }		
 


###################################			         
//end get  
##############################################################
?>