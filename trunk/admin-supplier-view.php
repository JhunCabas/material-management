<?php
include './resources/init.php';
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/admin-supplier-view.js"></script>
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
		<h2><?php echo $supplier->prepareName(); ?></h2>
		<table>
			<tr>
				<td class="caption" width="100">Address</td>
			</tr>
			<tr>
				<td width="100"><b>Line 1</b></td>
				<td><span id="desc" class="varInput"><?php echo $supplier->prepareName(); ?></span></td>
			</tr>
			<tr>
				<td width="100"><b>Line 2</b></td>
				<td><span id="desc" class="varInput"><?php echo $supplier->prepareName(); ?></span></td>
			</tr>
			<tr>
				<td class="caption" width="100">Contacts</td>
			</tr>
			<tr>
				<td width="100"><b>Contact Person</b></td>
				<td><span id="desc" class="varInput"><?php echo $supplier->prepareContactPerson(); ?></span></td>
			</tr>
			<tr>
				<td width="100"><b>Phone</b></td>
				<td><span id="desc" class="varInput"><?php echo $supplier->prepareContact(); ?></span></td>
			</tr>
			<tr>
				<td width="100"><b>Fax</b></td>
				<td><span id="desc" class="varInput"><?php echo $supplier->prepareFaxNo(); ?></span></td>
			</tr>
			<tr>
				<td class="caption" width="100">Others</td>
			</tr>
			<tr>
				<td width="100"><b>Information</b></td>
				<td><span id="desc" class="varInput"><?php echo $supplier->prepareInfo(); ?></span></td>
			</tr>
			<tr>
				<td id="iconCell">
					<ul id="icons" class="ui-widget ui-helper-clearfix">
						<li id="editBTN" title="Edit" class="view-btn ui-state-default ui-corner-all"><span class="ui-icon ui-icon-wrench"></span>Edit</li>
						<li id="saveBTN" title="Save" class="view-btn ui-state-default ui-corner-all" style="display:none;"><span class="ui-icon ui-icon-circle-check"></span>Save</li>
					</ul>						
				</td>
			</tr>
		</table>
		<?php
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>