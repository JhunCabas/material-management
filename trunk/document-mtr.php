<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-pr.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php $tmpl->place('menuDocument'); ?>
	<h2>Material Transfer Note</h2>
	<div class="form-frame span-23 last">
		<label for="doc_num">Document Number </label><input type="text" name="doc_num" value="" id="doc_num"/><br />
		<label for="doc_date">Document Date </label><input type="text" name="doc_date" value="" id="doc_date" class="datepicker"/><br />
		<label for="doc_type">Document Type </label><select type="text" name="doc_type" value="" id="doc_type">
			<?php Document_type::findAllOption();?></select><br />
	</div>
</div>
<?php $tmpl->place('footer'); ?>