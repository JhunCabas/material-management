<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/inventory-sub.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuInventory'); ?>
	<div id="main" class="span-24 last">
		<div id="categories span-24 last">
			<h2>Sub Category</h2>
				<form action = "inventory-sub.php" method = "get">
					<label for="maincc">Choose Main Category: </label>
					<span id="maincc">
						<select name = "maincc">
							<?php Inv_maincategory::findAllOption(); ?>
						</select>
					</span>
					<input id="submitBTN" type="submit" value="Submit" />
				</form>
			<?php
			if(isSet($_GET['maincc']))
			{
				try{
					$inv_subcategories = Inv_subcategory::findByMainCategoryCode($_GET['maincc']);
					$inv_maincategory = new Inv_maincategory($_GET['maincc']);
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
				printf("<br /><h3 id=\"mainCategoryTitle\" key=\"%s\">%s</h3>",$_GET['maincc'],$inv_maincategory->prepareDescription());
				printf("<table><thead><tr><td>Sub Category Code</td><td>Description</td><td>Main Category Code</td><td>Status</td></tr></thead><tbody>");
				foreach($inv_subcategories as $inv_subcategory)
				{
					printf("<tr><td class=\"hideFirst\"><span class=\"varInput\" id=\"Id\">%s</span></td>
								<td><span class=\"varInput\" id=\"Categorycode\">%s</span></td>
								<td><span class=\"varInput\" id=\"Description\">%s</span></td>
								<td><span class=\"varInput\" id=\"MainCategorycode\">%s</span></td>
								<td><span class=\"varInput\" id=\"Status\">%s</span></td>",
							$inv_subcategory->prepareId(),
							$inv_subcategory->prepareCategoryCode(),
							$inv_subcategory->prepareDescription(),
							$inv_subcategory->prepareMainCategoryCode(),
							($inv_subcategory->prepareStatus() == 1 ? "Active" : "Inactive"));
					printf("<td id=\"iconCell\" class=\"hideFirst\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
						<li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-check\"></span></li>
						<li id=\"cancel\" title=\"Cancel\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-close\"></span></li>
						</ul></td></tr>");
				}
			?>
				<tr id="newItem">
					<td>
						<span id="Categorycode">
							<input type="text" name="category_code" value="Input Category Code" />
						</span>
					</td>
					<td>
						<span id="Description">
							<input type="text" name="description" value="Input Description" />
						</span>
					</td>
					<td>
						<span id="MainCategorycode">
							<select name="main_category_code">
								<?php Inv_maincategory::findAllOption(); ?>
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