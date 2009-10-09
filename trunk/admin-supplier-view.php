<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/inventory-view.js"></script>
</head>
<body>
<div class = "container">
	<div id="View" class = "view-frame span-23 last">
		<?php
			if(!isSet($_GET['id']))
			{
				echo "<div class=\"ui-state-error ui-corner-all\">
						<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: 30px;\"></span>
						You arrived to this page in error</div>";
			}else{
				try{
					$supplier = new Supplier($_GET['id']);
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>