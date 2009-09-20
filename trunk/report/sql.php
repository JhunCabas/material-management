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
        $sql ="	SELECT * FROM umw_mms.inv_items A";
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

			         
//end get  
##############################################################
?>