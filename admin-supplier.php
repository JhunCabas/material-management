<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-supplier.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuAdmin'); ?>
	<h2>Administration</h2><h3>Supplier</h3>
	<table>
		<thead>
			<tr><th>Name</th><th>Contact</th><th>Information</th><th>Icon</th></tr>
		</thead>
		<tbody>
			<?php
				try{
					$suppliers = Supplier::findAll();
					foreach($suppliers as $supplier)
					{
						printf("<tr class=\"supplierRow\"><td id=\"sId\" class=\"hideFirst\">%s</td><td id=\"sName\" class=\"varInput\">%s</td><td id=\"sContact\" class=\"varInput\">%s</td><td id=\"sInfo\" class=\"varInput\">%s</td>"
							,$supplier->prepareId()
							,$supplier->prepareName()
							,$supplier->prepareContact()
							,$supplier->prepareInfo());
						printf("<td id=\"iconCell\" class=\"hideFirst\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
								<li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-check\"></span></li>
								<li id=\"cancel\" title=\"Cancel\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-close\"></span></li>
								</ul></td></tr>");
					}
				}	catch (fExpectedException $e) {
						echo $e->printMessage();
					}
			?>
			<tr id="newItem">
				<td><input id="supName" value="Input Name"></input></td>
				<td><input id="supContact" value="Input Contact"></input></td>
				<td><input id="supInfo" value="Input Information"></input></td>
				<td id="iconCell">
					<ul id="icons" class="ui-widget ui-helper-clearfix">
						<li id="add" title="Add" class="ui-state-default ui-corner-all">
							<span class="ui-icon ui-icon-circle-plus"></span>
						</li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?php $tmpl->place('footer'); ?>