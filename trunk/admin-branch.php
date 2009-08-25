<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-branch.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuAdmin'); ?>
	<h2>Administration</h2><h3>Branch</h3>
	<table>
		<thead>
			<tr><th>Id</th><th>Name</th><th>Location</th><th>Phone Number</th><th>Icon</th></tr>
		</thead>
			<tbody>
			<?php
				try{
					$branches = Branch::findAll();
					foreach($branches as $branch)
					{
						printf("<tr class=\"branchRow\"><td id=\"bId\">%s</td><td id=\"bName\" class=\"varInput\">%s</td><td id=\"bLocation\" class=\"varInput\">%s</td><td id=\"bPhone\" class=\"varInput\">%s</td>",
								$branch->prepareId(),
								$branch->prepareName(),
								$branch->prepareLocation(),
								$branch->preparePhoneNo());
						printf("<td id=\"iconCell\" class=\"hideFirst\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
								<li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-check\"></span></li>
								<li id=\"cancel\" title=\"Cancel\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-circle-close\"></span></li>
								</ul></td></tr>");
					}
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			?>
			<tr id="newItem">
				<td><input id="nuId" value="Input Id"></input></td>
				<td><input id="nuName" value="Input Name"></input></td>
				<td><input id="nuLocation" value="Input Location"></input></td>
				<td><input id="nuPhone" value="Input Phone"></input></td>
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