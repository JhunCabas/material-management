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
				$inv_item = new Inv_item($_GET['id']);
	?>
		<div id="content">
		<div id="Id" class="span-8"><h1 id="idCaption"><?php echo $inv_item->prepareId(); ?></h1>
			<?php
			 	try{
					$profile = new fImage($inv_item->getImageUrl()); 
					if($profile->getWidth() != 300)
					{
						$profile->resize(300,0);
						$profile->saveChanges();
						echo "Picture resized";
					}
					echo "<img class=\"view-img\" src=\"".$inv_item->prepareImageUrl()."\"></img>";
				} catch (fExpectedException $e) {
					if($e->getMessage() === "No filename was specified")
					{
						echo "No picture available, please upload.<br />";
					}
				}
			?>
			<form id="uploadBox" action="parser/Inv_item.php" method="POST" enctype="multipart/form-data">
			    <fieldset>
			        <p>
			            <label for="file">Image </label>
			            <input id="file" type="file" name="file" />
						<input name="type" type="hidden" value="upload" />
						<input name="hiddenId" type="hidden" value="<?php echo $inv_item->prepareId();?>"/>
						<input type="submit" value="Upload" />
			        </p>
			    </fieldset>
			</form>
		</div>
		<div id="ViewTable" class="span-14 last">
			<table>
				<tr>
					<td class="caption" width="100">Description</td>
					<td><span id="desc" class="varInput"><?php echo $inv_item->prepareDescription(); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Weight</td>
					<td><span id="weight" class="varInput"><?php echo $inv_item->prepareWeight(); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Dimension</td>
					<td><span id="dim" class="varInput"><?php echo $inv_item->prepareDimension(); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Part Number</td>
					<td><span id="part" class="varInput"><?php echo $inv_item->preparePartNumber(); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Unit of Measurement</td>
					<td><span id="uom" class="varInput"><?php echo $inv_item->prepareUnitOfMeasure(); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Rate</td>
					<td><span id="rate" class="varInput"><?php echo $inv_item->prepareRate(2); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Currency</td>
					<td><span id="curr" class="varInput"><?php echo $inv_item->prepareCurrency(); ?></span></td>
				</tr>
				<tr>
					<td class="caption">Purchase Year</td>
					<td><span id="pury" class="varInput"><?php echo $inv_item->preparePurchaseYear(); ?></td>
				</tr>
				<tr>
					<td class="caption">Detailed Description</td>
					<td><span id="detailed" class="varInput"><?php echo $inv_item->prepareDetailedDescription(); ?></td>
				</tr>
				<tr>
					<td class="caption">Status</td>
					<td><span id="statusVal" class="varInput"><?php echo Status::convert($inv_item->prepareStatus()); ?></td>
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
		</div>
		</div>
	<?php
			} catch (fExpectedException $e) {
				echo $e->printMessage();
			}
		}
	?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>