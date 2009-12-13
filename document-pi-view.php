<?php
include './resources/init.php';
fAuthorization::requireLoggedIn();
$tmpl->place('header');
?>
<script type="text/javascript" src="./js/document-pi-view.js"></script>
<?php
$tmpl->place('menu');
?>
<div id="content" class="span-24 last">
	<?php 
		$tmpl->place('menuDocument');
		if(!isSet($_GET['id']))
		{
			echo "<div class=\"span-24 ui-state-error ui-corner-all\">
					<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: 30px;\"></span>
					You arrived to this page in error</div>";
		}else{
			try{
				$production = new Production_issue($_GET['id']);
				$production_details = Production_issue_detail::findDetail($_GET['id']);
	?>
	<h2>Production Issue Form</h2>
  <div align=left> <a href=report/pi-pdf.php?PInum=<?=$_GET['id']?>><b>Download PDF</b></a></div><br>
	<div class="form-frame span-23 last">
		<h3>Production Issue Form</h3><br />
		<label for="doc_num">Document Number </label><span id="docNum"><?php echo $production->prepareDocNumber(); ?></span><br />
		<label for="doc_date">Document Date </label><?php echo $production->prepareDocDate("j F Y"); ?><br />
		<label for="doc_type">Document Type </label><?php echo $production->prepareDocType(); ?><br />
		<label for="branch_id">Branch </label>
				<?php $branch = new Branch($production->getBranchId()); echo $branch->prepareName() . " / " . "<span id=\"branchId\">" .$production->prepareBranchId()."</span>";?><br />
		<table id="formContent">
			<thead>
				<tr><th>No</th>
					<th>Item Code</th><th width="400">Description</th><th>Quantity</th><th>UOM</th><th>Remarks</th><th>Icons</th></tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					foreach($production_details as $production_detail)
					{
						echo "<tr id=\"rowNo".$counter."\"><td id=\"detailId\" class=\"hideFirst\">".$production_detail->prepareId()."</td><td>".$counter."</td><td id=\"itemCode\">".$production_detail->prepareItemId()."</td>";
						$item = new Inv_item($production_detail->getItemId());
						echo "<td>".$item->prepareDescription()."</td><td id=\"itemQuan\">".$production_detail->prepareQuantity()."</td>
							 	<td>".$item->prepareUnitOfMeasure()."</td><td>".$production_detail->prepareRemark()."</td>";
						if($production_detail->getStatus() == "pending")
							echo "<td id=\"iconCell\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">
								<li id=\"save\" title=\"Save\" class=\"ui-state-default ui-corner-all\">
									<span class=\"ui-icon ui-icon-disk\"></span>
									<span class=\"text-icon\">Save</span>
								</li>
								<li title=\"Loading\" class=\"hideFirst ui-corner-all\">
									<img src=\"./img/layout/ajax-loader2.gif\" />
								</li></ul></td></tr>";
						else
							echo "<td id=\"iconCell\"><ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\"><li title=\"Complete\" class=\"ui-state-default ui-corner-all\"><span class=\"ui-icon ui-icon-check\"></span></li><li title=\"Loading\" class=\"hideFirst ui-corner-all\"><img src=\"./img/layout/ajax-loader2.gif\" /></li></ul></td></tr>";
						$counter++;
					}
				?>
			</tbody>
		</table>
		<label>Notes</label><br /><?php echo $production->prepareNotes(); ?><br />
		<table id="approveContent">
			<tbody>
				<tr>
					<td><label>Issued and Received by </label></td>
						<?php 
							if($production->getIssuer()!=null)
								echo "<td>".$production->prepareIssuer()."</td>";
							else
								echo "<td id=\"issuer\"><input type=\"button\" value=\"Sign Here\" class=\"signHere\" /></td>";
						?></td><td><label>Date </label>
						<?php 
							if($production->getIssuer_date()!=null)
								echo $production->prepareIssuer_date("j F Y");
							else
								echo "<input type=\"text\" id=\"issDate\" class=\"datepicker\"></input>";
						?>
					</td>
				</tr>
		</table>
		<?php 
					if($production->getStatus() == 'pending')
					{
						echo "<input type=\"button\" id=\"cancelBTN\" value=\"Cancel\" style=\"float: right;\"/>";
						echo "<input type=\"button\" id=\"submitBTN\" value=\"Submit\" style=\"float: right;\"/>";
					}
					$me = fAuthorization::getUserToken(); 
					echo "<input type=\"hidden\" id=\"whoami\" value=\"".$me."\"/>";
				} catch (fExpectedException $e) {
					echo $e->printMessage();
				}
			}
		?>
	</div>
</div>
<?php $tmpl->place('footer'); ?>