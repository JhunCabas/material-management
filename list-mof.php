<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./resources/library/jquery.autocomplete/lib/jquery.bgiframe.min.js"></script>
<link media="screen, projection" href="./resources/library/jquery.autocomplete/jquery.autocomplete.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./js/document-pr.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Material Order Form</h2>
	<div class="span-20">
		<h3>Search</h3>
		<form>
		Search: <input></input> <br />
		Duration: 
		<select>
			<option>3 Months</option>
			<option>6 Months</option>
			<option>12 Months</option>
		</select>
		</form>
	</div>
	<br />
	<div class="span-20">
		<h3>Search Result</h3>
	</div>
</div>
<?php $tmpl->place('footer'); ?>