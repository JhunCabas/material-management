<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/inventory-main.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuInventory'); ?>
	<div id="main" class="span-24 last">
		<div id="categories span-24 last">
			<h2>Main Category</h2>
			<?php
				$inv_maincategories = Inv_maincategory::findAll();
				printf("<table><thead><tr><th>Main Category Code</th><th>Description</th><th>Status</th></tr></thead><tbody>");
				foreach ($inv_maincategories as $inv_maincategory) {
					printf("<tr><td><span class=\"varInput\" id=\"Categorycode\">%s</span></td>
								<td><span class=\"varInput\" id=\"Description\">%s</span></td>
								<td><span class=\"varInput\" id=\"Status\">%s</span></td>",
						$inv_maincategory->prepareCategoryCode()
						,$inv_maincategory->prepareDescription()
						,$inv_maincategory->prepareStatus() == 1 ? "Active" : "Inactive");
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
			?>
		</div>
	</div>
</div>
<?php
$tmpl->place('footer');
?>