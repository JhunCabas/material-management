<?
//error_reporting(E_ALL); 
include_once('class/FC_Colors.php');
include("class/FusionCharts.php");
include("sql.php");
extract($_GET);

switch ($op) {
    case "itempurchaseyear":
            $name = itemsPurchaseDate();
            $table = tablePurchaseDate();
            $caption = "Items Purchased in Years";
            $xaxis = "Purchase Year";
            $yaxis = "Number of Items";
            $graphType = "FCF_Column2D";
            $td = " <td><strong>Purchase Year</strong></td>
    <td><strong>Quantity</strong></td>";
            break;
    case "activeinactive":
            $name = itemActiveInactive();
            $table = tableActiveInactive();
            $caption = "Inventory Item Status";
            $xaxis = "Status";
            $yaxis = "Number of Items";
            $graphType = "FCF_Pie2D";
             $td = " <td><strong>Item Status </strong></td>
    <td><strong>Quantity</strong></td>";
            break;
    case "catvalue":
            $name = categoryValue();
            $table = tableCategoryValue();
            $caption = "Inventory total Value by Category";
            $xaxis = "Category";
            $yaxis = "Total Value";
            $graphType = "FCF_Pie2D";
             $td = " <td><strong>Category </strong></td>
    <td><strong>Total Value</strong></td>";
            break;
    case "branchvalue":
            $name = BranchValue();
            $table = tableBranchValue();
            $caption = "Inventory total Value by Branch";
            $xaxis = "Branch";
            $yaxis = "Total Value";
            $graphType = "FCF_Column2D";
             $td = " <td><strong>Category </strong></td>
    <td><strong>Total Value</strong></td>";
            break;        
    case 2:
            echo "i equals 2";
            break;
}
			  //end of if
					

						
$strXMLData= "<graph caption='$caption' subcaption='' xAxisName='$xaxis' yAxisName='$yaxis' numberPrefix='' showNames='1'  showValue='1' decimalPrecision='2'>".$name."</graph>";

echo renderChartHTML("charts/$graphType.swf", "", $strXMLData, "myNext", 600, 300);
?>

<h1><font color="#CCCCCC" size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong>
    <?php echo $caption; ?>
    </strong></font></h1>
      
<table width="300" border="2" cellspacing="0" cellpadding="0">
  
  <tr bordercolor="#000000" align="center" bordercolordark="#000000"> <font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    <?php 
				  
				  	echo $td;
					  ?>
   
    </font> </tr>
  <?php 
				  
				  	echo $table;
					  ?>
</table>