<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/list-mof.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Material Order Form</h2>
	<div class="span-20">
		<h3>Search</h3>
		<div class="searchBox span-23">
			<form action="list-mof.php">
			Search: <input name="search" type="text"></input>
			Duration: 
			<select name="month">
				<option value="3">3 Months</option>
				<option value="6">6 Months</option>
				<option value="12">12 Months</option>
			</select>
			<input id="submitBTN" type="submit" value="Submit" />
			</form>
		</div>
	</div>
	<br />
	
	<?php
		if(isSet($_GET['search']))
		{
			try{
				
				$purchaseEntries = Purchase::findByMof($_GET['search'],$_GET['month']);
	?>
		<div class="searchResult span-20">
			<h3>Search Result</h3>
			<table>
				<thead>
					<tr>
						<th>PR Number</th><th>PO Number</th><th>MOF Number</th>
					</tr>
				</thead>
				<tbody>
	<?php
				foreach($purchaseEntries as $purchaseEntry)
				{
					echo "<tr class=\"linkable\"><td class=\"docNumber PR\">".$purchaseEntry->prepareDocNumber()."</td>";
					if($purchaseEntry->getPoNumber() != NULL)
						echo "<td class=\"PO\">".$purchaseEntry->preparePoNumber()."</td>";
					else
						echo "<td>".$purchaseEntry->preparePoNumber()."</td>";
					echo "<td>".$purchaseEntry->prepareMofNumber()."</td></tr>";
				}
				echo "</tbody></table></div>";
				
			}catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	?>
</div>
<?php $tmpl->place('footer'); ?>