<?php
include './resources/init.php';
fAuthorization::requireAuthLevel('admin');
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-doctype.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuAdmin'); ?>
	<h2>Administration</h2><h3>Document Type</h3>
	<table>
		<thead><th>ID</th><th>Description</th><th>Icon</th></thead>
		<tbody>
			<?php
				try{
					$doctypes = Document_type::findAll();
					foreach($doctypes as $doctype)
					{
						printf("<tr class=\"doctypeRow\"><td id=\"dId\">%s</td><td id=\"dDesc\">%s</td></tr>"
							,$doctype->prepareId(),$doctype->prepareDescription());
					}
				}	catch (fExpectedException $e) {
						echo $e->printMessage();
					}
			?>
			<tr id="newItem">
				<td><input id="docID" value="Input Type"></input></td>
				<td><input id="docDesc" value="Input Description"></input></td>
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