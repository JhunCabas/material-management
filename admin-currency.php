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
			<tr><th>Country</th><th>Exchange</th><th>Month</th><th>Year</th><th>Icon</th></tr>
		</thead>
			<tbody>
			<?php
				try{
					$currencies = Currency::findAll();
					foreach($currencies as $currency)
					{
						$tempDate = new fDate($currency->getMonth());
						printf("<tr class=\"currencyRow\"><td class=\"hideFirst\" id=\"cId\">%s</td></td><td id=\"cCountry\" class=\"varInput\">%s</td><td id=\"cExchange\" class=\"varInput\">%s</td><td id=\"cMonth\" class=\"varInput\">%s</td><td id=\"cYear\" class=\"varInput\">%s</td>",
								$currency->prepareId(),
								$currency->prepareCountry(),
								$currency->prepareExchange(2),
								$tempDate->format('F'),
								$tempDate->format('Y'));
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
				<td><input id="nuExchange" value="Input Value"></input></td>
				<td>
					<select id="nuMonth">
						<option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
				</td>
				<td><input id="nuYear" value="Input Year"></td>
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