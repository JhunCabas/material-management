<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<link href="./resources/library/jquery.uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/inventory.js"></script>
<script type="text/javascript" src="./resources/library/jquery.uploadify/swfobject.js"></script>
<script type="text/javascript" src="./resources/library/jquery.uploadify/jquery.uploadify.v2.0.3.min.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuInventory'); ?>
	<div id="main" class="span-24 last">
		<div id="categories span-24 last">
			<h2>Inventory</h2>
				<form action = "inventory.php" method = "get">
					<label for="maincc">Choose Main Category: </label>
					<span id="maincc">
						<select name = "maincc">
							<option value="all">All</option>
							<?php Inv_maincategory::findAllOption(); ?>
						</select>
					</span>
					<label for="subcc">Choose Sub Category: </label>
					<span id="subcc">
						<select name = "subcc">
						</select>
					</span>
					<label for="classific">Choose Classification: </label>
					<span id="classific">
						<select name = "classific">
						</select>
					</span>
					<input id="submitBTN" type="submit" value="Submit" />
				</form>
			<?php
			if(isSet($_GET['maincc']))
			{
				try{					
					if(isSet($_GET['classific'])&&$_GET['classific']!="")
					{
						$inv_maincategory = new Inv_maincategory($_GET['maincc']);
						$inv_subcategory = new Inv_subcategory($_GET['subcc']);
						$inv_classification = new Inv_classification($_GET['classific']);
						$inv_items = Inv_item::findByClassificationCode($_GET['classific']);
						printf("<br /><h3 id=\"mainCategoryTitle\" key=\"%s\">%s</h3>",$_GET['maincc'],$inv_maincategory->prepareDescription());
						printf("<h4 id=\"subCategoryTitle\" key=\"%s\">%s > <span id=\"classificationTitle\" key=\"%s\"><b>%s</b></span></h4>",$_GET['subcc'],$inv_subcategory->prepareDescription(),$_GET['subcc'],$inv_classification->prepareDescription());
					}
					else if(isSet($_GET['subcc'])&&$_GET['subcc']!="")
					{
						$inv_maincategory = new Inv_maincategory($_GET['maincc']);
						$inv_subcategory = new Inv_subcategory($_GET['subcc']);
						$inv_items = Inv_item::findBySubCategoryCode($_GET['subcc']);
						printf("<h4 id=\"subCategoryTitle\" key=\"%s\">%s</h4>",$_GET['subcc'],$inv_subcategory->prepareDescription());
						printf("<br /><h3 id=\"mainCategoryTitle\" key=\"%s\">%s</h3>",$_GET['maincc'],$inv_maincategory->prepareDescription());
					}
					else if($_GET['maincc']!="all")
					{	
						$inv_maincategory = new Inv_maincategory($_GET['maincc']);
						$inv_items = Inv_item::findByMainCategoryCode($_GET['maincc']);
						printf("<br /><h3 id=\"mainCategoryTitle\" key=\"%s\">%s</h3>",$_GET['maincc'],$inv_maincategory->prepareDescription());
					}
					else
					{
						if(!isSet($_GET['page'])||$_GET['page']==1)
						{
							$first = 0;
							$last = 30;
						}else
						{
							$first = ($_GET['page'] - 1)*30 + 1;
							$last = $first + 30 - 1;
						}
						$inv_counter = count(Inv_item::findAll());
						$inv_items = Inv_item::findAllLimit($first,$last);
						//echo "FIRST:". $first ."LAST:". $last;
						$times = ceil($inv_counter/30);
						//$times = 30;
						echo "<h3>All results</h3>";
						if($times < 2)
						{
							echo "<span id=\"pagination\"><a href=\"inventory.php?maincc=all\">First </a>";
							echo "<a href=\"inventory.php?maincc=all&page=$times\">Last</a></span>";
						}else
						{
							echo "<span id=\"pagination\"><a href=\"inventory.php?maincc=all\">First </a>";
							for($i=1;$i<$times;$i++)
							{
								echo "<a href=\"inventory.php?maincc=all&page=$i\">$i </a></span>";
							}
							echo "<a href=\"inventory.php?maincc=all&page=$times\">Last</a></span>";
						}
					}
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}

				printf("<table class=\"scrollContent\"><thead><tr><th>Item ID</th><th>Item Description</th><th>UOM</th><th>Dimension</th><th>Weight</th><th>Standard Rate</th><th>Status</th></tr></thead><tbody>");
				foreach($inv_items as $inv_item)
				{
					printf("<tr class=\"linkable\"><td id=\"itemID\"><span>%s</span></td>
								<td><span>%s</span></td>
								<td><span>%s</span></td>
								<td><span>%s</span></td>
								<td><span>%s</span></td>
								<td><span>%s</span></td>
								<td><span>%s</span></td></tr>",
							$inv_item->prepareId(),
							$inv_item->prepareDescription(),
							$inv_item->prepareUnitOfMeasure(),
							$inv_item->prepareDimension(),
							$inv_item->prepareWeight(),
							$inv_item->prepareRate(2),
							Status::convert($inv_item->prepareStatus()));
				}
			?>
				<tr id="newItem">
					<td id="iconCell" class="addIcon" colspan="7">
						<ul id="icons" class="ui-widget ui-helper-clearfix">
							<li id="add" title="Add" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-plus"></span></li>
							<li>Add New Item</li>
						</ul>
					</td>
				</tr>
				<tr id="newItem" class="addItem hideFirst">
					<td colspan="7">
						<form id="addDetail">
							<label for="addmaincc">Choose Main Category: </label>
								<select id="addmaincc" name = "addmaincc">
									<?php Inv_maincategory::findAllOption(); ?>
								</select>
							<label for="addsubcc">Choose Sub Category: </label>
								<select id="addsubcc" name = "addsubcc"></select>
							<label for="addclassific">Choose Classification: </label>
								<select id="addclassific" name = "addclassific"></select><br />
							<table id="tableDetail" class="hideFirst">
							<tbody>
								<tr><td>Item Code: </td><td><input type="hidden" id="idInput"></input><span id="idSpan"></span><br /></td></tr>
								<tr><td>Description: </td><td><input type="text" id="desc"></input><br /></td></tr>
								<tr><td>Weight: </td><td><input type="text" id="weight"></input><br /></td></tr>
								<tr><td>Dimension: </td><td><input type="text" id="dim"></input><br /></td></tr>
								<tr><td>Part Number: </td><td><input type="text" id="part"></input><br /></td></tr>
								<tr><td>Unit of Measurement: </td><td><input type="text" id="uom"></input><br /></td></tr>
								<tr><td>Rate: </td><td><input type="text" id="rate"></input><br /></td></tr>
								<tr><td>Currency: </td><td><input type="text" id="curr"></input><br /></td></tr>
								<tr><td>Purchase Year: </td><td><input type="text" id="pury"></input><br /></td></tr>
								<tr><td>Detailed Description: </td><td><input type="text" id="detailed"></input><br /></td></tr>
								<tr><td>Status: </td><td><select id="statusVal"><?php Status::printOption(); ?></select></td></tr>
							</tbody>
							<tfoot>
								<tr><td colspan="2"><input id="addBTN" type="button" value="Submit" /></td></tr>
							</tfoot>
							</table>
						</form>
					</td>
				</tr>
			<?php
				echo "</tbody></table>";
			}
			?>
		</div>
	</div>
</div>
	<div id="uploadBox">Item Added. <br /> Proceed to upload image here. <br /> <span id="uploadify"></span></div>
<?php $tmpl->place('footer'); ?>