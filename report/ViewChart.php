<?
//error_reporting(E_ALL); 
include_once('class/FC_Colors.php');
include("class/FusionCharts.php");
include("sql.php");
extract($_GET);

switch ($op) {
    case "itempurchaseyear":
            $name = itemsPurchaseDate();
            $caption = "Items Purchased in Years";
            $xaxis = "Purchase Year";
            $yaxis = "Number of Items";
            $graphType = "FCF_Column2D";
            break;
    case "activeinactive":
            $name = itemActiveInactive();
            $caption = "Inventory Items Status";
            $xaxis = "Status";
            $yaxis = "Number of Items";
            $graphType = "FCF_Pie2D";
            break;
    case 2:
            echo "i equals 2";
            break;
}
			  //end of if
					

						
$strXMLData= "<graph caption='$caption' subcaption='' xAxisName='$xaxis' yAxisName='$yaxis' numberPrefix='' showNames='1' decimalPrecision='0'>".$name."</graph>";

echo renderChartHTML("charts/$graphType.swf", "", $strXMLData, "myNext", 600, 300);
?>