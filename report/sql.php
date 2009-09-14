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
//end get
##############################################################
?>