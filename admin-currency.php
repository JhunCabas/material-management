<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-currency.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuAdmin'); ?>
	<h2>Administration</h2><h3>Currency</h3>
	<table>
		<thead>
			<tr><th>Country</th><th>Month</th><th>Icon</th></tr>
		</thead>
			<tbody>
			<?php
				try{
					$currencies = Currency::findAll();
					foreach($currencies as $currency)
					{
						printf("<tr class=\"currencyRow\"><td id=\"cCountry\" class=\"varInput\">%s</td><td id=\"cMonth\" class=\"varInput\">%s</td>",
								$branch->prepareCountry(),
								$branch->prepareMonth());
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
				<td><input id="nuCountry" value="Input Country"></input></td>
				<td><input id="nuMonth" value="Input Month"></input></td>
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