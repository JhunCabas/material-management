<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/inventory-classification.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuInventory'); ?>
	<div id="main" class="span-24 last">
		<div id="categories span-24 last">
			<h2>Classification</h2>
				<form action = "inventory-classification.php" method = "get">
					<label for="maincc">Choose Main Category: </label>
					<span id="maincc">
						<select name = "maincc">
							<?php Inv_maincategory::findAllOption(); ?>
						</select>
					</span>
					<label for="subcc"> Choose Sub Category: </label>
					<span id="subcc">
						<select name = "subcc">
						</select>
					</span>
					<input id="submitBTN" type="submit" value="Submit" />
				</form>
			<?php
			if(isSet($_GET['maincc']))
			{
				try{
					$inv_subcategories = Inv_subcategory::findByMainCategoryCode($_GET['maincc']);
					$inv_classifications = Inv_classification::findBySubCategoryCode($_GET['subcc']);
					$inv_maincategory = new Inv_maincategory($_GET['maincc']);
					$inv_subcategory = new Inv_subcategory($_GET['subcc']);
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
				printf("<br /><h3 id=\"mainCategoryTitle\" key=\"%s\">%s</h3>",$_GET['maincc'],$inv_maincategory->prepareDescription());
				printf("<h4 id=\"subCategoryTitle\" key=\"%s\">%s</h4>",$_GET['subcc'],$inv_subcategory->prepareDescription());
				printf("<table><thead><tr><td>Classification Code</td><td>Description</td><td>Sub Category Code</td><td>Status</td></tr></thead><tbody>");
				foreach($inv_classifications as $inv_classification)
				{
					printf("<tr><td class=\"hideFirst\"><span class=\"varInput\" id=\"Id\">%s</span></td>
								<td><span class=\"varInput\" id=\"Classificationcode\">%s</span></td>
								<td><span class=\"varInput\" id=\"Description\">%s</span></td>
								<td><span class=\"varInput\" id=\"SubCategorycode\">%s</span></td>
								<td><span class=\"varInput\" id=\"Status\">%s</span></td>",
							$inv_classification->prepareId(),
							$inv_classification->prepareClassificationCode(),
							$inv_classification->prepareDescription(),
							$inv_classification->prepareSubCategoryCode(),
							($inv_classification->prepareStatus() == 1 ? "Active" : "Inactive"));
					printf("<td id=\"iconCell\" class=\"hideFirst\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
						<li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-check\"></span></li>
						<li id=\"cancel\" title=\"Cancel\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-close\"></span></li>
						</ul></td></tr>");
				}
			?>
				<tr id="newItem">
					<td>
						<span id="Classificationcode">
							<input type="text" name="classification_code" value="Input Classification Code" />
						</span>
					</td>
					<td>
						<span id="Description">
							<input type="text" name="description" value="Input Description" />
						</span>
					</td>
					<td>
						<span id="SubCategorycode">
							<select name="sub_category_code">
								<?php Inv_subcategory::findOptionByMainCategoryCode($_GET['maincc']); ?>
							</select>
						</span>
					</td>
					<td>
						<span id="Status">
							<select>
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
						</span>
					</td>
					<td id="iconCell">
						<ul id="icons" class="ui-widget ui-helper-clearfix">
							<li id="add" title="Save" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-circle-check"></span></li>
						</ul>						
					</td>
				</tr>
			<?php
				echo "</tbody>";
				echo "</table>";
			}
			?>
		</div>
	</div>
</div>
<?php $tmpl->place('footer'); ?>